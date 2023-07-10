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

namespace tacd\collections\traits\objects\magical_accesser;

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
                    $keys[$map_key][$name]  = $argument->{\sprintf('get%s', $name)}();
                }
            }

            $build_spec_list[$idx]   = [
                'unique_id' => static::createUniqueKey($argument),
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
            $this->collectionPursedNameMap[$map_key] = \explode('In', $map_key);
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
            $this->collectionPursedNameMap[$map_key] = \explode('In', $map_key);
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
            default => throw new \Exception(\sprintf('Error: method not found %s()', $method_name)),
        };
    }
}
