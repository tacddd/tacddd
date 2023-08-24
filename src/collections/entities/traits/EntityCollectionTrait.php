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

namespace tacddd\collections\entities\traits;

/**
 * エンティティコレクション特性
 */
trait EntityCollectionTrait
{
    /**
     * @var entity[] コレクション
     */
    protected array $collection = [];

    /**
     * @return array オプション
     */
    protected array $options;

    /**
     * @var string[] キャッシュマップ
     */
    protected array $cacheMap = [];

    /**
     * @var array 逆引きキャッシュマップ
     */
    protected array $reverseCacheMap = [];

    /**
     * @var array エレメントが持つパブリックメソッドリスト
     */
    protected array $entityMethodList  = [];

    /**
     * 指定されたエンティティからユニークIDを返します。
     *
     * @param  entity     $entity エンティティ
     * @return string|int ユニークID
     */
    abstract public static function createUniqueId(object $entity): string|int;

    /**
     * 受け入れ可能なクラスを返します。
     *
     * @return string 受け入れ可能なクラス
     */
    abstract public static function getAllowedClass(): string;

    /**
     * 与えられたエンティティからユニークIDを抽出して返します。
     *
     * @param  entity     $entity エンティティ
     * @return string|int ユニークID
     */
    public static function extractUniqueId(object $entity): string|int
    {
        if (!static::isAllowedClass($entity)) {
            throw new \TypeError(\sprintf('受け入れ可能外のクラスを指定されました。class:%s, allowed_class:%s', $entity::class, static::getAllowedClass()));
        }

        $unique_key = static::createUniqueId($entity);

        if (!\is_string($unique_key) && !\is_int($unique_key)) {
            $unique_key = static::adjustKey($unique_key, 'UniqueId');
        }

        return $unique_key;
    }

    /**
     * 受け入れ可能なクラスかどうかを返します。
     *
     * @param  string $class クラスパス
     * @return bool   受け入れ可能なクラスかどうか
     */
    public static function isAllowedClass(object|string $class): bool
    {
        $allowed_class  = static::getAllowedClass();

        if ($class instanceof $allowed_class) {
            return true;
        }

        if (\is_a($class, $allowed_class, true)) {
            return true;
        }

        if (\is_subclass_of($class, $allowed_class, true)) {
            return true;
        }

        return false;
    }

    /**
     * キーがstring|intではなかった場合に調整して返します。
     *
     * @param  mixed       $key        キー
     * @param  null|string $method_key メソッドキー
     * @return string|int  調整済みキー
     */
    public static function adjustKey(mixed $key, ?string $method_key = null): string|int
    {
        return $key;
    }

    /**
     * constructor
     *
     * @param iterable $entities 初期状態として受け入れるエンティティの配列
     * @param array    $options  オプション
     */
    public function __construct(iterable $entities = [], array $options = [])
    {
        $this->options  = $options;

        $this->addAll($entities);
    }

    public function test()
    {
        return $this->collection;
    }

    /**
     * エンティティを追加します。
     *
     * @param  entity $entity エンティティ
     * @return static このインスタンス
     */
    public function add(object $entity): static
    {
        if (!static::isAllowedClass($entity)) {
            throw new \TypeError(\sprintf('受け入れ可能外のクラスを指定されました。class:%s, allowed_class:%s', $entity::class, static::getAllowedClass()));
        }

        $unique_key = static::extractUniqueId($entity);

        foreach ($this->reverseCacheMap[$unique_key] ?? [] as $cache_key => $criteria_keys) {
            $this->setCache($cache_key, $entity, $this->createCriteriaForCache($criteria_keys, $entity));
        }

        $this->collection[$unique_key]  = $entity;

        return $this;
    }

    /**
     * エンティティを纏めて追加します。
     *
     * @param  iterable $entities エンティティ
     * @return static   このインスタンス
     */
    public function addAll(iterable $entities): static
    {
        foreach ($entities as $entity) {
            $this->add($entity);
        }

        return $this;
    }

    /**
     * エンティティがコレクションに含まれているかどうかを返します。
     *
     * @param  entity $entity 検索対象
     * @return bool   エンティティが存在するかどうか
     */
    public function contains(object $entity): bool
    {
        return \array_key_exists(static::extractUniqueId($entity), $this->collection);
    }

    /**
     * 指定したエンティティが全てコレクションに含まれているかどうかを返します。
     *
     * @param  iterable $entities 検索対象
     * @return bool     エンティティが存在するかどうか
     */
    public function containsAll(iterable $entities): bool
    {
        foreach ($entities as $entity) {
            if (!\array_key_exists(static::extractUniqueId($entity), $this->collection)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 指定したエンティティの何れかがコレクションに含まれているかどうかを返します。
     *
     * @param  iterable $entities 検索対象
     * @return bool     エンティティが存在するかどうか
     */
    public function containsAny(iterable $entities): bool
    {
        foreach ($entities as $entity) {
            if (\array_key_exists(static::extractUniqueId($entity), $this->collection)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 指定した検索条件のエンティティが存在するかどうかを返します。
     *
     * @param  array $criteria 検索条件
     * @return bool  エンティティが存在するかどうか
     */
    public function hasBy(array $criteria): bool
    {
        $cache_map  = $this->loadCacheMap($criteria);

        foreach ($criteria as $key => $value) {
            if (\is_object($value)) {
                $value  = $this->adjustKey($value, $key);
            }

            if (\array_key_exists($value, $cache_map)) {
                $cache_map = $cache_map[$value];
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * 指定されたキーのエンティティを返します。
     *
     * @param  int|string|object $unique_id ユニークID
     * @return null|object       エンティティ
     */
    public function find(int|string|object $unique_id): ?object
    {
        return $this->collection[\is_object($unique_id) ? static::extractUniqueId($unique_id) : $unique_id] ?? null;
    }

    /**
     * コレクションの全エンティティを返します。
     *
     * @return array コレクションの全エンティティ
     */
    public function findAll(): array
    {
        return $this->collection;
    }

    /**
     * 指定したキーのエンティティを探して返します。
     *
     * @param  array    $criteria 検索条件
     * @param  array    $orderBy  ソート設定
     * @return object[] 検索結果
     */
    public function findBy(array $criteria, array $orderBy = []): array
    {
        $cache_map  = $this->loadCacheMap($criteria);

        $not_found  = false;

        foreach ($criteria as $key => $value) {
            if (\is_object($value)) {
                $value  = $this->adjustKey($value, $key);
            }

            if (\array_key_exists($value, $cache_map)) {
                $cache_map = $cache_map[$value];
            } else {
                $not_found  = true;

                break;
            }
        }

        if ($not_found) {
            return [];
        }

        $result         = [];

        foreach ($cache_map as $unique_id) {
            if (($entity = $this->collection[$unique_id] ?? null) !== null) {
                $result[]   = $entity;
            }
        }

        return $result;
    }

    /**
     * 指定したキーのエンティティを探して返します。
     *
     * @param  array  $criteria 検索条件
     * @param  array  $orderBy  ソート設定
     * @return object 検索結果
     */
    public function findOneBy(array $criteria, array $orderBy = []): ?object
    {
        $unique_id  = $this->loadCacheMap($criteria);

        $not_found  = false;

        foreach ($criteria as $key => $value) {
            if (\is_object($value)) {
                $value  = $this->adjustKey($value, $key);
            }

            if (\array_key_exists($value, $unique_id)) {
                $unique_id = $unique_id[$value];
            } else {
                $not_found  = true;

                break;
            }
        }

        if ($not_found) {
            return null;
        }

        $unique_id  = $unique_id[\array_key_first($unique_id)];

        return $this->collection[$unique_id] ?? null;
    }

    /**
     * 指定したキーのエンティティを探して返します。
     *
     * @param  array $criteria 検索条件
     * @param  array $map_keys マップキー
     * @param  array $order_by ソート設定
     * @return array エンティティ
     */
    public function findToMapBy(array $criteria, array $map_keys = [], array $order_by = []): array
    {
        $cache_map  = $this->loadCacheMap($criteria);

        $not_found  = false;

        foreach ($criteria as $key => $value) {
            $criteria_keys[]    = $key;

            if (\is_object($value)) {
                $value  = $this->adjustKey($value, $key);
            }

            if (\array_key_exists($value, $cache_map)) {
                $cache_map  = $cache_map[$value];
            } else {
                $not_found  = true;

                break;
            }
        }

        if ($not_found) {
            return [];
        }

        $result     = [];

        $map_keys   = empty($map_keys) ? $criteria_keys : $map_keys;

        foreach ($cache_map as $unique_id) {
            if (($entity = $this->collection[$unique_id] ?? null) === null) {
                continue;
            }

            $in_nest_map_key     = [];

            foreach ($map_keys as $map_key) {
                $method_key = \ucfirst(\strtr(\ucwords(\strtr($map_key, ['_' => ' '])), [' ' => '']));

                $in_nest_map_key[$map_key]  = static::adjustKey(
                    $entity->{'get' . $method_key}(),
                    $method_key,
                );
            }

            $tmp = &$result;

            $last_idx   = \array_key_last($in_nest_map_key);
            $target_key = $in_nest_map_key[$last_idx];
            unset($in_nest_map_key[$last_idx]);

            foreach ($in_nest_map_key as $key) {
                if (!\array_key_exists($key, $tmp)) {
                    $tmp[$key] = [];
                }

                $tmp = &$tmp[$key];
            }

            if (\array_key_exists($target_key, $tmp) && \is_array($tmp[$target_key])) {
                $tmp[$target_key][] = $entity;
            } else {
                $tmp[$target_key] = [$entity];
            }

            unset($tmp);
        }

        return $result;
    }

    /**
     * 指定したキーのエンティティを探して返します。
     *
     * @param  array $criteria 検索条件
     * @param  array $map_keys マップキー
     * @param  array $order_by ソート設定
     * @return array エンティティ
     */
    public function findOneToMapBy(array $criteria, array $map_keys = [], array $order_by = []): array
    {
        $cache_map  = $this->loadCacheMap($criteria);

        $not_found  = false;

        foreach ($criteria as $key => $value) {
            $criteria_keys[]    = $key;

            if (\is_object($value)) {
                $value  = $this->adjustKey($value, $key);
            }

            if (\array_key_exists($value, $cache_map)) {
                $cache_map  = $cache_map[$value];
            } else {
                $not_found  = true;

                break;
            }
        }

        if ($not_found) {
            return [];
        }

        $result     = [];

        $map_keys   = empty($map_keys) ? $criteria_keys : $map_keys;

        foreach ($cache_map as $unique_id) {
            if (($entity = $this->collection[$unique_id] ?? null) === null) {
                continue;
            }

            $in_nest_map_key     = [];

            foreach ($map_keys as $map_key) {
                $method_key = \ucfirst(\strtr(\ucwords(\strtr($map_key, ['_' => ' '])), [' ' => '']));

                $in_nest_map_key[$map_key]  = static::adjustKey(
                    $entity->{'get' . $method_key}(),
                    $method_key,
                );
            }

            $tmp = &$result;

            $last_idx   = \array_key_last($in_nest_map_key);
            $target_key = $in_nest_map_key[$last_idx];
            unset($in_nest_map_key[$last_idx]);

            foreach ($in_nest_map_key as $key) {
                if (!\array_key_exists($key, $tmp)) {
                    $tmp[$key] = [];
                }

                $tmp = &$tmp[$key];
            }

            if (!\array_key_exists($target_key, $tmp)) {
                $tmp[$target_key] = $entity;
            }

            unset($tmp);
        }

        return $result;
    }

    /**
     * エンティティを取り外します。
     *
     * @param  entity $entity エンティティ
     * @return static このインスタンス
     */
    public function remove(object $entity): static
    {
        unset($this->collection[static::extractUniqueId($entity)]);

        return $this;
    }

    /**
     * 指定したキーのエンティティを取り外します。
     *
     * @param  array  $criteria 検索条件
     * @return static このインスタンス
     */
    public function removeBy(array $criteria): static
    {
        $cache_map  = $this->loadCacheMap($criteria);

        $not_found  = false;

        foreach ($criteria as $key => $value) {
            if (\is_object($value)) {
                $value  = $this->adjustKey($value, $key);
            }

            if (\array_key_exists($value, $cache_map)) {
                $cache_map = $cache_map[$value];
            } else {
                $not_found  = true;

                break;
            }
        }

        if ($not_found) {
            return $this;
        }

        foreach ($cache_map as $unique_id) {
            unset($this->collection[$unique_id]);
        }

        return $this;
    }

    /**
     * このコレクションをクリアします。
     *
     * @return static このインスタンス
     */
    public function clear(): static
    {
        $this->collection = [];

        return $this;
    }

    /**
     * コレクションの最初の要素を返します。
     *
     * @return object コレクションの最初の要素
     */
    public function first(): ?object
    {
        return $this->collection[\array_key_first($this->collection)] ?? null;
    }

    /**
     * コレクションの最後の要素を返します。
     *
     * @return object コレクションの最後の要素
     */
    public function last(): ?object
    {
        return $this->collection[\array_key_last($this->collection)] ?? null;
    }

    /**
     * 現在のコレクション数を返します。
     *
     * @return int 現在のコレクション数
     */
    public function count(): int
    {
        return \count($this->collection);
    }

    /**
     * 現在のコレクションが空かどうか返します。
     *
     * @return bool 現在のコレクションが空かどうか
     */
    public function empty(): bool
    {
        return empty($this->collection);
    }

    /**
     * コレクションを指定したキーの階層構造を持つマップに変換して返します。
     *
     * @return array コレクションマップ
     */
    public function toMap(array $map_keys): array
    {
        $cache_map = $this->loadCacheMap(\array_flip($map_keys));

        $this->findForNestedArray($cache_map, false);

        return $cache_map;
    }

    /**
     * コレクションを指定したキーの階層構造を持つマップに変換して返します。
     *
     * @return array コレクションマップ
     */
    public function toOneMap(array $map_keys): array
    {
        $cache_map = $this->loadCacheMap(\array_flip($map_keys));

        $this->findForNestedArray($cache_map, true);

        return $cache_map;
    }

    /**
     * イテレータを返します。
     *
     * @return \Traversable イテレータ
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->collection);
    }

    /**
     * コレクションの配列表現を返します。
     *
     * @return array コレクションの配列表現
     */
    public function toArray(): array
    {
        return $this->collection;
    }

    /**
     * 受け入れるクラスが持つパブリックメソッドのリストを返します。
     *
     * @return array 受け入れるクラスが持つパブリックメソッドのリスト
     */
    protected function getObjectMethodList(): array
    {
        if (empty($this->entityMethodList)) {
            $entity_method_list    = [];

            foreach ((new \ReflectionClass(static::getAllowedClass(9)))->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                $method_name    = $method->getName();

                if (!\str_starts_with($method_name, 'get')) {
                    continue;
                }

                $entity_method_list[$method->getName()]    = [
                    'map_key'   => $map_key = \mb_substr($method_name, 4),
                    'length'    => \mb_strlen($map_key),
                ];
            }

            \uksort($entity_method_list, function($a, $b): int {
                return \strlen($b) <=> \strlen($a) ?: \strnatcmp($b, $a);
            });

            $this->entityMethodList    = $entity_method_list;
        }

        return $this->entityMethodList;
    }

    /**
     * キャッシュキーを作成し返します。
     *
     * @param  array  $criteria 検索条件
     * @return string キャッシュキー
     */
    protected function createCacheKey(array $criteria): string
    {
        $find_cache_key = [];

        foreach ($criteria as $key => $value) {
            $find_cache_key[]  = \ucfirst(\strtr(\ucwords(\strtr($key, ['_' => ' '])), [' ' => '']));
        }

        \sort($find_cache_key);

        return \implode('In', $find_cache_key);
    }

    /**
     * キャッシュ用検索条件を構築します。
     *
     * @param  array  $criteria_keys 検索条件キー
     * @param  object $entity        エンティティ
     * @return array  キャッシュ用検索条件
     */
    protected function createCriteriaForCache(array $criteria_keys, object $entity): array
    {
        $criteria   = [];

        foreach ($criteria_keys as $key) {
            $method_key = \ucfirst(\strtr(\ucwords(\strtr($key, ['_' => ' '])), [' ' => '']));

            $criteria[$key] = static::adjustKey(
                $entity->{'get' . $method_key}(),
                $method_key,
            );
        }

        return $criteria;
    }

    /**
     * キャッシュをセットします。
     *
     * @param string $cache_key キャッシュキー
     * @param object $entity    キャッシュに設定するエンティティ
     * @param array  $criteria  検索条件
     */
    protected function setCache(
        string $cache_key,
        object $entity,
        array $criteria,
    ): static {
        $in_nest_list   = [];

        $criteria_keys  = [];

        $unique_key = static::extractUniqueId($entity);

        foreach ($criteria as $key => $value) {
            $criteria_keys[]    = $key;

            $method_key = \ucfirst(\strtr(\ucwords(\strtr($key, ['_' => ' '])), [' ' => '']));

            $in_nest_list[] = static::adjustKey(
                $entity->{'get' . $method_key}(),
                $method_key,
            );
        }

        $this->reverseCacheMap[$unique_key][$cache_key] = $criteria_keys;

        $tmp = &$this->cacheMap[$cache_key];

        $last_idx   = \array_key_last($in_nest_list);
        $target_key = $in_nest_list[$last_idx];
        unset($in_nest_list[$last_idx]);

        foreach ($in_nest_list as $key) {
            if (!\array_key_exists($key, $tmp)) {
                $tmp[$key] = [];
            }

            $tmp = &$tmp[$key];
        }

        if (\array_key_exists($target_key, $tmp)) {
            foreach ($tmp[$target_key] as $idx => $uk) {
                if ($uk === $unique_key) {
                    unset($tmp[$target_key][$idx]);
                }
            }

            $tmp[$target_key][] = $unique_key;
        } else {
            $tmp[$target_key] = [$unique_key];
        }

        unset($tmp);

        return $this;
    }

    /**
     * キャッシュを構築します。
     *
     * @param array $criteria 検索条件
     */
    protected function loadCacheMap(array $criteria): array
    {
        $cache_key = $this->createCacheKey($criteria);

        if (!\array_key_exists($cache_key, $this->cacheMap)) {
            $this->cacheMap[$cache_key] = [];

            foreach ($this->collection as $entity) {
                $this->setCache($cache_key, $entity, $criteria);
            }
        }

        return $this->cacheMap[$cache_key];
    }

    /**
     * 階層構造を持つ配列の最終段の値をキーにし、マッチするエンティティに置換して返します。
     *
     * @param  array $array 階層構造を持つ配列
     * @param  bool  $trim  最終段の配列を単数かするかどうか
     * @return bool  最終段かどうか
     */
    protected function findForNestedArray(array &$array, bool $trim): bool
    {
        foreach ($array as $idx => &$value) {
            if (\is_array($value)) {
                if (true === $this->findForNestedArray($value, $trim)) {
                    if ($trim) {
                        $value  = $value[\array_key_first($value)];
                    } else {
                        foreach ($value as $in_idx => $tmp) {
                            if ($tmp === null) {
                                unset($value[$in_idx]);
                            }
                        }
                    }
                }
            } else {
                $value = $this->collection[$value];

                if ($trim) {
                    return true;
                }
            }

            if (empty($value)) {
                unset($array[$idx]);
            }
        }

        return false;
    }
}
