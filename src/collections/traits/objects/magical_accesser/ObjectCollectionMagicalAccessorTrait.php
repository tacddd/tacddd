<?php
/**
 *   _____          ____  ____  ____
 *  |_   ___ _  ___|  _ \|  _ \|  _ \
 *    | |/ _` |/ __| | | | | | | | | |
 *    | | (_| | (__| |_| | |_| | |_| |
 *    |_|\__,_|\___|____/|____/|____/
 *
 * @category    TacDDD
 * @package     TacDDD
 * @author      wakaba <wakabadou@gmail.com>
 * @copyright   Copyright (c) @2023  Wakabadou (http://www.wakabadou.net/) / Project ICKX (https://ickx.jp/). All rights reserved.
 * @license     http://opensource.org/licenses/MIT The MIT License.
 *              This software is released under the MIT License.
 * @varsion     1.0.0
 */

declare(strict_types=1);

namespace tacddd\collections\traits\objects\magical_accesser;

/**
 * オブジェクトコレクションマジックアクセス特性
 */
trait ObjectCollectionMagicalAccessorTrait
{
    /**
     * @var object[] コレクション
     */
    protected array $collection = [];

    /**
     * @var array コレクションキーマップ
     */
    protected array $collectionKeyMap = [];

    /**
     * @var array コレクションキー逆引きマップ
     */
    protected array $collectionKeyReverseMap = [];

    /**
     * @var array 解析済み名前マップ
     */
    protected array $collectionPursedNameMap = [];

    /**
     * @var array エレメントが持つパブリックメソッドリスト
     */
    protected array $elementMethodList  = [];

    /**
     * 受け入れ可能なクラスを返します。
     *
     * @return string|array 受け入れ可能なクラス
     */
    abstract public static function getAllowedClasses(): string|array;

    /**
     * 指定されたオブジェクトからユニークキーを返します。
     *
     * @param  object     $element オブジェクト
     * @return int|string ユニークキー
     */
    abstract public static function createUniqueKey(object $element): string|int;

    /**
     * 受け入れ可能なクラスかどうかを返します。
     *
     * @param  string $class クラスパス
     * @return bool   受け入れ可能なクラスかどうか
     */
    public static function isAllowedClass(object|string $class): bool
    {
        foreach (\is_array($allowed_classes = static::getAllowedClasses()) ? $allowed_classes : $allowed_classes = [$allowed_classes] as $allowed_class) {
            if ($class instanceof $allowed_class) {
                return true;
            }

            if (\is_subclass_of($class, $allowed_class, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * キーがstring|intではなかった場合に調整して返します。
     *
     * @param  mixed      $key キー
     * @return string|int 調整済みキー
     */
    public static function adjustKey(mixed $key): string|int
    {
        return $key;
    }

    /**
     * 受け入れるクラスが持つパブリックメソッドのリストを返します。
     *
     * @return array 受け入れるクラスが持つパブリックメソッドのリスト
     */
    protected function getElementMethodList(): array
    {
        if (empty($this->elementMethodList)) {
            $element_method_list    = [];

            foreach (\is_array($allowed_classes = $this->getAllowedClasses()) ? $allowed_classes : [$allowed_classes] as $allowed_class) {
                foreach ((new \ReflectionClass($allowed_class))->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                    $method_name    = $method->getName();

                    if (!\str_starts_with($method_name, 'get')) {
                        continue;
                    }

                    $element_method_list[$method->getName()]    = [
                        'map_key'   => $map_key = \mb_substr($method_name, 4),
                        'length'    => \mb_strlen($map_key),
                    ];
                }
            }

            \uksort($element_method_list, function($a, $b): int {
                return \strlen($b) <=> \strlen($a) ?: \strnatcmp($b, $a);
            });

            $this->elementMethodList    = $element_method_list;
        }

        return $this->elementMethodList;
    }

    /**
     * マップキーをパースして返します。
     *
     * @param  string $map_key マップキー
     * @return array  キー配列
     */
    protected function parseMapKey(string $map_key): array
    {
        if (!\str_contains($map_key, 'In')) {
            return [$map_key];
        }

        return \explode('In', $map_key);
    }

    /**
     * 構築に必要な情報を構築して返します。
     *
     * @param  object[] $arguments                  取り込み対象のオブジェクト配列
     * @param  array    $collection_pursed_name_map 解析済み名前マップ
     * @return array    構築に必要な情報
     */
    protected function createBuildSpecList(array $arguments, array $collection_pursed_name_map): array
    {
        $build_spec_list    = [];

        foreach ($arguments as $idx => $argument) {
            $keys   = [];

            foreach ($collection_pursed_name_map as $map_key => $names) {
                foreach ($names as $name) {
                    $keys[$map_key][$name]  = static::adjustKey($argument->{\sprintf('get%s', $name)}());
                }
            }

            $unique_key = static::createUniqueKey($argument);

            $build_spec_list[$idx]   = [
                'unique_id' => $unique_key,
                'keys'      => $keys,
            ];
        }

        return $build_spec_list;
    }

    /**
     * 配列の指定した階層に値を設定します。
     *
     * @param  array $array 配列
     * @param  array $keys  階層
     * @param  mixed $value 値
     * @return array 値を設定した配列
     */
    protected function setInNest(array $array, array $keys, mixed $value): array
    {
        $tmp = &$array;

        $last_idx   = \array_key_last($keys);
        $target_key = $keys[$last_idx];
        unset($keys[$last_idx]);

        foreach ($keys as $key) {
            if (!\array_key_exists($key, $tmp)) {
                $tmp[$key] = [];
            }

            $tmp = &$tmp[$key];
        }

        $tmp[$target_key] = $value;

        unset($tmp);

        return $array;
    }

    /**
     * 配列の指定した階層の値を除去します。
     *
     * @param  array $array 配列
     * @param  array $keys  階層
     * @return array 値を除去した配列
     */
    protected function removeInNest(array $array, array $keys): array
    {
        $tmp = &$array;

        $last_idx   = \array_key_last($keys);
        $target_key = $keys[$last_idx];
        unset($keys[$last_idx]);

        foreach ($keys as $key) {
            if (\array_key_exists($key, $tmp)) {
                $tmp = &$tmp[$key];
            } else {
                return $array;
            }
        }

        unset($tmp[$target_key]);

        return $array;
    }

    /**
     * Magical group
     *
     * @param  string $map_key マップ名
     * @return static このインスタンス
     */
    protected function magicalGroup(
        string $map_key,
    ): array {
        if (!isset($this->collectionKeyMap[$map_key])) {
            $this->collectionKeyMap[$map_key]  = [];
        }

        if (!isset($this->collectionPursedNameMap[$map_key])) {
            $this->collectionPursedNameMap[$map_key] = $this->parseMapKey($map_key);
        }

        $collection_pursed_name_map = $this->collectionPursedNameMap[$map_key];

        $last_idx   = \array_key_last($collection_pursed_name_map);
        $last_name  = $collection_pursed_name_map[$last_idx];
        unset($collection_pursed_name_map[$last_idx]);

        $group  = [];

        foreach ($this->collection as $element) {
            $tmp    = &$group;

            $last_key = static::adjustKey($element->{\sprintf('get%s', $last_name)}());

            foreach ($collection_pursed_name_map as $name) {
                $key = static::adjustKey($element->{\sprintf('get%s', $name)}());

                if (!\array_key_exists($key, $tmp)) {
                    $tmp[$key] = [];
                }

                $tmp = &$tmp[$key];
            }

            $tmp[$last_key] = $element;

            unset($tmp);
        }

        return $group;
    }

    /**
     * Magical remove
     *
     * @param  array  $arguments 引数
     * @param  string $map_key   マップ名
     * @return static このインスタンス
     */
    protected function magicalRemove(
        array $arguments,
        string $map_key,
    ): static {
        if (!isset($this->collectionPursedNameMap[$map_key])) {
            $this->collectionPursedNameMap[$map_key] = $this->parseMapKey($map_key);
        }

        $tmp = $this->collectionKeyMap[$map_key];

        foreach (\is_array($arguments[0]) ? $arguments[0] : $arguments as $key) {
            if (!\array_key_exists($key, $tmp)) {
                return $this;
            }

            $tmp = $tmp[$key];
        }

        $unique_id  = $tmp;

        foreach ($this->collectionKeyReverseMap[$unique_id] as $work_map_key => $reverse_key_list) {
            foreach ($reverse_key_list as $reverse_keys) {
                $this->collectionKeyMap[$work_map_key] = $this->removeInNest(
                    $this->collectionKeyMap[$work_map_key] ?? [],
                    $reverse_keys,
                );
            }
        }

        unset($this->collection[$unique_id]);

        return $this;
    }

    /**
     * Magical has
     *
     * @param  array $arguments          引数
     * @param  array $collection_key_map コレクションキーマップ
     * @return bool  値が存在するかどうか
     */
    protected function magicalHas(
        array $arguments,
        array $collection_key_map,
    ): bool {
        foreach (\is_array($arguments[0]) ? $arguments[0] : $arguments as $map_key) {
            if (!\array_key_exists($map_key, $collection_key_map)) {
                return false;
            }

            $collection_key_map = $collection_key_map[$map_key];
        }

        return \array_key_exists($collection_key_map, $this->collection);
    }

    /**
     * Magical get
     *
     * @param  array $arguments          引数
     * @param  array $collection_key_map コレクションキーマップ
     * @return mixed 値
     */
    protected function magicalGet(
        array $arguments,
        array $collection_key_map,
    ): mixed {
        foreach (\is_array($arguments[0]) ? $arguments[0] : $arguments as $map_key) {
            if (!\array_key_exists($map_key, $collection_key_map)) {
                return null;
            }

            $collection_key_map = $collection_key_map[$map_key];
        }

        return $this->collection[$collection_key_map] ?? null;
    }

    /**
     * Magical set
     *
     * @param  array  $arguments 引数
     * @param  string $map_key   マップ名
     * @return static このインスタンス
     */
    protected function magicalSet(
        array $arguments,
        string $map_key,
    ): static {
        if (!isset($this->collectionKeyMap[$map_key])) {
            $this->collectionKeyMap[$map_key]  = [];
        }

        if (!isset($this->collectionPursedNameMap[$map_key])) {
            $this->collectionPursedNameMap[$map_key] = $this->parseMapKey($map_key);
        }

        $build_spec_list    = $this->createBuildSpecList($arguments, $this->collectionPursedNameMap);

        foreach ($arguments as $idx => $argument) {
            $build_spec = $build_spec_list[$idx];

            $keys_list  = $build_spec['keys'];
            $unique_id  = $build_spec['unique_id'];

            foreach ($this->collectionKeyReverseMap[$unique_id] ?? [] as $work_map_key => $reverse_key_list) {
                foreach ($reverse_key_list as $reverse_keys) {
                    $this->collectionKeyMap[$work_map_key] = $this->removeInNest(
                        $this->collectionKeyMap[$work_map_key] ?? [],
                        $reverse_keys,
                    );
                }

                $keys                                    = $keys_list[$work_map_key];
                $this->collectionKeyMap[$work_map_key]   = $this->setInNest(
                    $this->collectionKeyMap[$work_map_key] ?? [],
                    $keys,
                    $unique_id,
                );

                $this->collectionKeyReverseMap[$unique_id][$work_map_key][\implode('_', $keys)]   = $keys;
            }

            $keys       = $keys_list[$map_key];

            $this->collectionKeyMap[$map_key]  = $this->setInNest(
                $this->collectionKeyMap[$map_key],
                $keys,
                $unique_id,
            );

            $this->collection[$unique_id] = $argument;

            $this->collectionKeyReverseMap[$unique_id][$map_key][\implode('_', $keys)]   = $keys;
        }

        return $this;
    }

    /**
     * Magical method
     *
     * @param  string $method_name メソッド名
     * @param  array  $arguments   引数
     * @return mixed  返り値
     */
    public function __call(string $method_name, array $arguments): mixed
    {
        $map_key        = '';
        $action_name    = '';

        foreach ([
            'removeBy'  => ['length' => 8],
            'groupBy'   => ['length' => 7],
            'getBy'     => ['length' => 5],
            'setBy'     => ['length' => 5],
            'hasBy'     => ['length' => 5],
        ] as $action => $spec) {
            if (\str_starts_with($method_name, $action)) {
                $map_key        = \mb_substr($method_name, $spec['length']);
                $action_name    = \mb_substr($action, 0, $spec['length'] - 2);

                break;
            }
        }

        if ($map_key === null) {
            throw new \Exception(\sprintf('マッチするパターンのメソッドがありません。method_name:%s', $method_name));
        }

        if (($collection_key_map = $this->collectionKeyMap[$map_key] ?? null) === null) {
            $this->collectionKeyMap[$map_key] = [];

            if ($action_name !== 'set') {
                $set_method_name    = \sprintf('setBy%s', $map_key);

                foreach ($this->collection as $element) {
                    $this->$set_method_name($element);
                }
            }

            $collection_key_map = $this->collectionKeyMap[$map_key];
        }

        return match ($action_name) {
            'group'     => $this->magicalGroup(
                map_key  : $map_key,
            ),
            'remove'    => $this->magicalRemove(
                arguments           : $arguments,
                map_key             : $map_key,
            ),
            'has'       => $this->magicalHas(
                arguments           : $arguments,
                collection_key_map  : $collection_key_map,
            ),
            'get'       => $this->magicalGet(
                arguments           : $arguments,
                collection_key_map  : $collection_key_map,
            ),
            'set'       => $this->magicalSet(
                arguments           : $arguments,
                map_key             : $map_key,
            ),
            default     => throw new \Exception(\sprintf('Error: method not found %s()', $method_name)),
        };
    }
}
