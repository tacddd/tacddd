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

namespace tacddd\collections\objects\traits;

use tacddd\collections\objects\enums\KeyAccessTypeEnum;
use tacddd\collections\objects\traits\ObjectCollectionInterface;

/**
 * オブジェクトコレクション特性
 */
trait ObjectCollectionTrait
{
    /**
     * @var null|\Closure JSONシリアライザ
     */
    protected readonly ?\Closure $jsonSerializer;

    /**
     * @var bool JSONシリアライザが有効かどうか
     */
    protected readonly bool $enabledJsonSerializer;

    /**
     * @var object[] コレクション
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
     * @var array アクセスキーキャッシュ
     */
    protected array $accessKeyCache = [];

    /**
     * @var array オブジェクトが持つアクセスポイントリスト
     */
    protected array $objectAccessPointList  = [];

    /**
     * 指定された値からユニークIDを返します。
     *
     * @param  mixed      $value 値
     * @return int|string ユニークID
     */
    abstract public static function createUniqueId(mixed $value): string|int;

    /**
     * 受け入れ可能なクラスを返します。
     *
     * @return string 受け入れ可能なクラス
     */
    abstract public static function getAllowedClass(): string;

    /**
     * 与えられたオブジェクトからユニークIDを抽出して返します。
     *
     * @param  object     $object オブジェクト
     * @return string|int ユニークID
     */
    public static function extractUniqueId(object $object): string|int
    {
        if (!static::isAllowedClass($object)) {
            throw new \TypeError(\sprintf('%sに受け入れ可能外のクラスを指定されました。class:%s, allowed_class:%s', static::class, $object::class, static::getAllowedClass()));
        }

        $unique_id = static::createUniqueId($object);

        if (!\is_string($unique_id) && !\is_int($unique_id)) {
            $unique_id = static::normalizeKey($unique_id, 'UniqueId');
        }

        return $unique_id;
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
     * @param  null|string $access_key アクセスキー
     * @return string|int  調整済みキー
     */
    public static function normalizeKey(mixed $key, ?string $access_key = null): string|int
    {
        return $key;
    }

    /**
     * オブジェクトの値の取得の仕方を返します。
     *
     * @return KeyAccessTypeEnum オブジェクトの値の取得の仕方
     */
    protected static function getKeyAccessType(): KeyAccessTypeEnum
    {
        return KeyAccessTypeEnum::Method;
    }

    /**
     * constructor
     *
     * @param iterable $objects 初期状態として受け入れるオブジェクトの配列
     * @param array    $options オプション
     */
    public function __construct(iterable $objects = [], array $options = [])
    {
        // ==============================================
        // options
        // ==============================================
        $this->options  = $options;

        // jsonSerializer
        $this->enabledJsonSerializer    = \array_key_exists(static::OPTION_JSON_SERIALIZER, $this->options) && $this->options[static::OPTION_JSON_SERIALIZER] instanceof \Closure;
        $this->jsonSerializer           = $this->enabledJsonSerializer ? $this->options[static::OPTION_JSON_SERIALIZER] : null;

        // ==============================================
        // objects add
        // ==============================================
        $this->addAll($objects);
    }

    /**
     * このコレクションを元に新しいコレクションを作成して返します。
     *
     * @param iterable $objects 初期状態として受け入れるオブジェクトの配列
     * @return static 新しいコレクション
     */
    public function with(iterable $objects = []): static
    {
        return new static($objects, $this->options);
    }

    /**
     * オブジェクトを追加します。
     *
     * @param  object $object オブジェクト
     * @return static このインスタンス
     */
    public function add(object $object): static
    {
        if (!static::isAllowedClass($object)) {
            throw new \TypeError(\sprintf('%sに受け入れ可能外のクラスを指定されました。class:%s, allowed_class:%s', static::class, $object::class, static::getAllowedClass()));
        }

        $unique_id = static::extractUniqueId($object);

        foreach ($this->reverseCacheMap[$unique_id] ?? [] as $cache_key => $criteria_keys) {
            $this->setCache($cache_key, $object, $this->createCriteriaForCache($criteria_keys, $object));
        }

        $this->collection[$unique_id]  = $object;

        return $this;
    }

    /**
     * オブジェクトを纏めて追加します。
     *
     * @param  iterable|object $objects オブジェクト
     * @return static          このインスタンス
     */
    public function addAll(iterable|object $objects, iterable|object ...$args): static
    {
        if (\is_iterable($objects)) {
            foreach ($objects as $object) {
                $this->add($object);
            }
        } else {
            $this->add($objects);
        }

        if (!empty($args)) {
            foreach ($args as $objects) {
                if (\is_iterable($objects)) {
                    foreach ($objects as $object) {
                        $this->add($object);
                    }
                } else {
                    $this->add($objects);
                }
            }
        }

        return $this;
    }

    /**
     * オブジェクトを纏めて追加します。
     *
     * @param  iterable $collection オブジェクト
     * @return static   このインスタンス
     */
    public function merge(iterable $collection): static
    {
        foreach ($collection as $object) {
            $this->add($object);
        }

        return $this;
    }

    /**
     * オブジェクトがコレクションに含まれているかどうかを返します。
     *
     * @param  object $object 検索対象
     * @return bool   オブジェクトが存在するかどうか
     */
    public function contains(object $object): bool
    {
        return \array_key_exists(static::extractUniqueId($object), $this->collection);
    }

    /**
     * 指定したオブジェクトが全てコレクションに含まれているかどうかを返します。
     *
     * @param  iterable $objects 検索対象
     * @return bool     オブジェクトが存在するかどうか
     */
    public function containsAll(iterable $objects): bool
    {
        foreach ($objects as $object) {
            if (!\array_key_exists(static::extractUniqueId($object), $this->collection)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 指定したオブジェクトの何れかがコレクションに含まれているかどうかを返します。
     *
     * @param  iterable $objects 検索対象
     * @return bool     オブジェクトが存在するかどうか
     */
    public function containsAny(iterable $objects): bool
    {
        foreach ($objects as $object) {
            if (\array_key_exists(static::extractUniqueId($object), $this->collection)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 指定した検索条件のオブジェクトが存在するかどうかを返します。
     *
     * @param  array $criteria 検索条件
     * @return bool  オブジェクトが存在するかどうか
     */
    public function hasBy(array $criteria): bool
    {
        $cache_map  = $this->loadCacheMap($criteria);

        foreach ($criteria as $key => $value) {
            if (\is_object($value)) {
                $value  = $this->normalizeKey($value, $key);
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
     * 指定されたユニークキーのオブジェクトを返します。
     *
     * @param  int|string|object $unique_id ユニークID
     * @return null|object       オブジェクト
     */
    public function find(int|string|object $unique_id): ?object
    {
        return $this->collection[\is_object($unique_id) ? static::extractUniqueId($unique_id) : $unique_id] ?? null;
    }

    /**
     * 指定されたユニークキーのオブジェクトの指定されたアクセスキーの値を返します。
     *
     * @param  int|string|object $unique_id ユニークID
     * @param  string            $map_key   マップキー
     * @return mixed             指定されたキーのオブジェクトの指定されたアクセスキーの値
     */
    public function findValue(int|string|object $unique_id, string $map_key): mixed
    {
        $object = $this->collection[\is_object($unique_id) ? static::extractUniqueId($unique_id) : $unique_id] ?? null;

        if ($object === null) {
            return $object;
        }

        $keyAccessType    = $this->getKeyAccessType();

        \array_key_exists($map_key, $this->accessKeyCache) ?: $this->setAccessKeyCache($map_key, $keyAccessType);

        return match ($keyAccessType->name) {
            KeyAccessTypeEnum::Property->name     => $object->{$this->accessKeyCache[$map_key]},
            KeyAccessTypeEnum::ArrayAccess->name  => $object[$this->accessKeyCache[$map_key]],
            default                               => $object->{$this->accessKeyCache[$map_key]}(),
        };
    }

    /**
     * コレクションの全オブジェクトの指定された値を返します。
     *
     * @param  string $map_key マップキー
     * @return array  コレクションの全オブジェクトの指定された値
     */
    public function findValueAll(string $map_key): array
    {
        $result = [];

        $keyAccessType    = $this->getKeyAccessType();

        foreach ($this->collection as $idx => $object) {
            \array_key_exists($map_key, $this->accessKeyCache) ?: $this->setAccessKeyCache($map_key, $keyAccessType);

            $result[$idx]   = match ($keyAccessType->name) {
                KeyAccessTypeEnum::Property->name     => $object->{$this->accessKeyCache[$map_key]},
                KeyAccessTypeEnum::ArrayAccess->name  => $object[$this->accessKeyCache[$map_key]],
                default                               => $object->{$this->accessKeyCache[$map_key]}(),
            };
        }

        return $result;
    }

    /**
     * 指定したキーのオブジェクトを探して返します。
     *
     * @param  array  $criteria 検索条件
     * @param  array  $options  オプション
     * @return static 検索結果
     */
    public function findBy(array $criteria, array $options = []): static
    {
        return new static($this->findByAsArray($criteria, $options), $this->options);
    }

    /**
     * 指定したキーのオブジェクトを探して配列として返します。
     *
     * @param  array    $criteria 検索条件
     * @param  array    $options  オプション
     * @return object[] 検索結果
     */
    public function findByAsArray(array $criteria, array $options = []): array
    {
        $cache_map  = $this->loadCacheMap($criteria);

        $not_found  = false;

        foreach ($criteria as $key => $value) {
            if (\is_object($value)) {
                $value  = $this->normalizeKey($value, $key);
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
            if (($object = $this->collection[$unique_id] ?? null) !== null) {
                $result[]   = $object;
            }
        }

        return $result;
    }

    /**
     * コレクションをフィルタして返します。
     *
     * @param  \Closure $criteria   フィルタ条件
     * @param  array    $options    オプション
     * @return static   検索結果
     */
    public function filterBy(\Closure $criteria, array $options = []): static
    {
        return new static($this->filterByAsArray($criteria, $options), $this->options);
    }

    /**
     * コレクションをフィルタして配列として返します。
     *
     * @param  \Closure $criteria   フィルタ条件
     * @param  array    $options    オプション
     * @return static   検索結果
     */
    public function filterByAsArray(\Closure $criteria, array $options = []): array
    {
        $result = [];

        foreach ($this->collection as $unique_kue => $object) {
            if ($criteria($object, $unique_kue, $options)) {
                $result[]   = $object;
            }
        }

        return $result;
    }

    /**
     * 指定したキーのオブジェクトの値を探して返します。
     *
     * @param  array    $criteria 検索条件
     * @param  array    $map_key  マップキー
     * @param  string   $collection_class コレクションクラスパス
     * @param  array    $options  オプション
     * @return object[] 検索結果
     */
    public function findValueBy(array $criteria, string $map_key, string $collection_class, array $options = []): ObjectCollectionInterface
    {
        if (!\is_subclass_of($collection_class, ObjectCollectionInterface::class, true)) {
            throw new \RuntimeException(\sprintf('%sは%sを実装している必要があります。', $collection_class, ObjectCollectionInterface::class));
        }

        return new $collection_class($this->findValueByAsArray($criteria, $map_key));
    }

    /**
     * 指定したキーのオブジェクトの値を探して返します。
     *
     * @param  array    $criteria 検索条件
     * @param  string   $map_key  マップキー
     * @param  array    $options  オプション
     * @return object[] 検索結果
     */
    public function findValueByAsArray(array $criteria, string $map_key, array $options = []): array
    {
        $cache_map  = $this->loadCacheMap($criteria);

        $not_found  = false;

        foreach ($criteria as $key => $value) {
            if (\is_object($value)) {
                $value  = $this->normalizeKey($value, $key);
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

        $result = [];

        $keyAccessType  = $this->getKeyAccessType();

        foreach ($cache_map as $unique_id) {
            if (($object = $this->collection[$unique_id] ?? null) !== null) {
                \array_key_exists($map_key, $this->accessKeyCache) ?: $this->setAccessKeyCache($map_key, $keyAccessType);

                $result[]   = match ($keyAccessType->name) {
                    KeyAccessTypeEnum::Property->name     => $object->{$this->accessKeyCache[$map_key]},
                    KeyAccessTypeEnum::ArrayAccess->name  => $object[$this->accessKeyCache[$map_key]],
                    default                               => $object->{$this->accessKeyCache[$map_key]}(),
                };
            }
        }

        return $result;
    }

    /**
     * 指定したキーのオブジェクトを探して返します。
     *
     * @param  array  $criteria 検索条件
     * @param  array  $options  オプション
     * @return object 検索結果
     */
    public function findOneBy(array $criteria, array $options = []): ?object
    {
        $unique_id  = $this->loadCacheMap($criteria);

        $not_found  = false;

        foreach ($criteria as $key => $value) {
            if (\is_object($value)) {
                $value  = $this->normalizeKey($value, $key);
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
     * 指定したキーのオブジェクトを探して値を返します。
     *
     * @param  array  $criteria 検索条件
     * @param  string $map_key  マップキー
     * @param  array  $options  オプション
     * @return mixed  検索結果
     */
    public function findValueOneBy(array $criteria, string $map_key, array $options = []): mixed
    {
        $unique_id  = $this->loadCacheMap($criteria);

        $not_found  = false;

        foreach ($criteria as $key => $value) {
            if (\is_object($value)) {
                $value  = $this->normalizeKey($value, $key);
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

        $keyAccessType  = $this->getKeyAccessType();

        if (($object = $this->collection[$unique_id] ?? null) !== null) {
            \array_key_exists($map_key, $this->accessKeyCache) ?: $this->setAccessKeyCache($map_key, $keyAccessType);

            return match ($keyAccessType->name) {
                KeyAccessTypeEnum::Property->name     => $object->{$this->accessKeyCache[$map_key]},
                KeyAccessTypeEnum::ArrayAccess->name  => $object[$this->accessKeyCache[$map_key]],
                default                               => $object->{$this->accessKeyCache[$map_key]}(),
            };
        }

        return null;
    }

    /**
     * 指定したキーのオブジェクトを探して返します。
     *
     * @param  array $criteria 検索条件
     * @param  array $map_keys マップキー
     * @param  array $order_by オプション
     * @return array オブジェクト
     */
    public function findToMapBy(array $criteria, array $map_keys = [], array $order_by = []): array
    {
        $cache_map  = $this->loadCacheMap($criteria);

        $not_found  = false;

        foreach ($criteria as $key => $value) {
            $criteria_keys[]    = $key;

            if (\is_object($value)) {
                $value  = $this->normalizeKey($value, $key);
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

        $keyAccessType    = $this->getKeyAccessType();

        foreach ($cache_map as $unique_id) {
            if (($object = $this->collection[$unique_id] ?? null) === null) {
                continue;
            }

            $in_nest_map_key     = [];

            foreach ($map_keys as $map_key) {
                \array_key_exists($map_key, $this->accessKeyCache) ?: $this->setAccessKeyCache($map_key, $keyAccessType);

                $in_nest_map_key[$map_key]  = static::normalizeKey(
                    match ($keyAccessType->name) {
                        KeyAccessTypeEnum::Property->name     => $object->{$this->accessKeyCache[$map_key]},
                        KeyAccessTypeEnum::ArrayAccess->name  => $object[$this->accessKeyCache[$map_key]],
                        default                               => $object->{$this->accessKeyCache[$map_key]}(),
                    },
                    $this->accessKeyCache[$map_key],
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
                $tmp[$target_key][] = $object;
            } else {
                $tmp[$target_key] = [$object];
            }

            unset($tmp);
        }

        return $result;
    }

    /**
     * 指定したキーのオブジェクトを探して返します。
     *
     * @param  array $criteria 検索条件
     * @param  array $map_keys マップキー
     * @param  array $order_by オプション
     * @return array オブジェクト
     */
    public function findOneToMapBy(array $criteria, array $map_keys = [], array $order_by = []): array
    {
        $cache_map  = $this->loadCacheMap($criteria);

        $not_found  = false;

        foreach ($criteria as $key => $value) {
            $criteria_keys[]    = $key;

            if (\is_object($value)) {
                $value  = $this->normalizeKey($value, $key);
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

        $keyAccessType    = $this->getKeyAccessType();

        foreach ($cache_map as $unique_id) {
            if (($object = $this->collection[$unique_id] ?? null) === null) {
                continue;
            }

            $in_nest_map_key     = [];

            foreach ($map_keys as $map_key) {
                \array_key_exists($map_key, $this->accessKeyCache) ?: $this->setAccessKeyCache($map_key, $keyAccessType);

                $in_nest_map_key[$map_key]  = static::normalizeKey(
                    match ($keyAccessType->name) {
                        KeyAccessTypeEnum::Property->name     => $object->{$this->accessKeyCache[$map_key]},
                        KeyAccessTypeEnum::ArrayAccess->name  => $object[$this->accessKeyCache[$map_key]],
                        default                               => $object->{$this->accessKeyCache[$map_key]}(),
                    },
                    $this->accessKeyCache[$map_key],
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
                $tmp[$target_key] = $object;
            }

            unset($tmp);
        }

        return $result;
    }

    /**
     * オブジェクトを取り外します。
     *
     * @param  object $object オブジェクト
     * @return static このインスタンス
     */
    public function remove(object $object): static
    {
        $unique_id  = static::extractUniqueId($object);

        $key_map        = [];

        $keyAccessType    = $this->getKeyAccessType();

        foreach ($this->reverseCacheMap[$unique_id] ?? [] as $cache_key => $criteria_keys) {
            $in_nest_list   = [];

            foreach ($criteria_keys as $map_key) {
                if (!\array_key_exists($map_key, $key_map)) {
                    \array_key_exists($map_key, $this->accessKeyCache) ?: $this->setAccessKeyCache($map_key, $keyAccessType);

                    $key_map[$map_key] = static::normalizeKey(
                        match ($keyAccessType->name) {
                            KeyAccessTypeEnum::Property->name     => $object->{$this->accessKeyCache[$map_key]},
                            KeyAccessTypeEnum::ArrayAccess->name  => $object[$this->accessKeyCache[$map_key]],
                            default                               => $object->{$this->accessKeyCache[$map_key]}(),
                        },
                        $this->accessKeyCache[$map_key],
                    );
                }

                $in_nest_list[] = $key_map[$map_key];
            }

            $tmp        = &$this->cacheMap[$cache_key];

            foreach ($in_nest_list as $in_nest) {
                $tmp    = &$tmp[$in_nest];
            }

            if ($tmp === null) {
                continue;
            }

            $is_empty       = false;

            foreach ($tmp as $idx => $value) {
                if ($value === $unique_id) {
                    unset($tmp[$idx]);

                    $is_empty   = empty($tmp);
                }
            }

            unset($tmp);

            if ($is_empty) {
                $tmp        = &$this->cacheMap[$cache_key];

                $refs   = [];
                $keys   = [];

                foreach ($in_nest_list as $in_nest) {
                    if (\is_array($tmp)) {
                        if (empty($tmp)) {
                            unset($tmp);
                        } else {
                            $refs[] = &$tmp;
                            $keys[] = $in_nest;
                        }
                    }

                    $tmp    = &$tmp[$in_nest];
                }

                foreach (\array_reverse(\array_keys($keys)) as $idx) {
                    $in_nest    = $keys[$idx];

                    if (empty($refs[$idx][$in_nest])) {
                        unset($refs[$idx][$in_nest]);
                    }
                }

                if (empty($this->cacheMap[$cache_key])) {
                    unset($this->cacheMap[$cache_key]);
                }

                unset($tmp);
            }
        }

        unset($this->collection[$unique_id]);

        return $this;
    }

    /**
     * 指定したキーのオブジェクトを取り外します。
     *
     * @param  array  $criteria 検索条件
     * @return static このインスタンス
     */
    public function removeBy(array $criteria): static
    {
        $this->remove($this->findOneBy($criteria));

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
     * @param  array $map_keys 階層構造キー
     * @return array コレクションマップ
     */
    public function toMap(array $map_keys): array
    {
        $cache_map  = $this->loadCacheMap(\array_flip($map_keys));

        \array_walk_recursive($cache_map, function(&$data): void {
            $data = $this->collection[$data];
        });

        return $cache_map;
    }

    /**
     * コレクションを指定したキーの階層構造を持ち、単一のオブジェクトを持つマップに変換して返します。
     *
     * @param  array $map_keys 階層構造キー
     * @return array コレクションマップ
     */
    public function toOneMap(array $map_keys): array
    {
        $cache_map  = $this->loadCacheMap(\array_flip($map_keys));

        $this->replaceCahceMapToOne($cache_map);

        return $cache_map;
    }

    /**
     * 指定したキーの値を持つ配列マップを返します。
     *
     * @param  array $map_keys 階層構造キー
     * @return array 指定したキーの値を持つ配列マップ
     */
    public function toArrayMap(array $map_keys): array
    {
        $keyAccessType    = $this->getKeyAccessType();

        $cache_map  = $this->loadCacheMap(\array_flip($map_keys));

        \array_walk_recursive($cache_map, function(&$data) use ($map_keys, $keyAccessType): void {
            $map = [];

            foreach ($map_keys as $map_key) {
                $access_key     = $this->accessKeyCache[$map_key];

                $map[$map_key]  = match ($keyAccessType->name) {
                    KeyAccessTypeEnum::Property->name     => $this->collection[$data]->{$access_key},
                    KeyAccessTypeEnum::ArrayAccess->name  => $this->collection[$data][$access_key],
                    default                               => $this->collection[$data]->{$access_key}(),
                };
            }

            $data = $map;
        });

        return $cache_map;
    }

    /**
     * 指定したキーの単一の値を持つ配列マップを返します。
     *
     * @param  array $map_keys 階層構造キー
     * @return array 指定したキーの値を持つ配列マップ
     */
    public function toArrayOneMap(array $map_keys): array
    {
        $cache_map  = $this->loadCacheMap(\array_flip($map_keys));

        $this->replaceCahceMapToArrayOne(
            $cache_map,
            $map_keys,
            $this->getKeyAccessType(),
        );

        return $cache_map;
    }

    /**
     * 指定したキーの値を持つ配列マップを返します。
     *
     * @param  array                    $map_keys 階層構造キー
     * @param  null|int|string|callable $target   取得対象 省略時は 階層構造キーの第一要素を使用する
     * @return array                    指定したキーの値を持つ配列マップ
     */
    public function getArrayMap(array $map_keys, null|int|string|callable $target = null): array
    {
        $cache_map  = $this->loadCacheMap(\array_flip($map_keys));

        $this->replaceCahceMapGetArrayOne(
            $cache_map,
            $target ?? $map_keys[\array_key_first($map_keys)],
            $this->getKeyAccessType(),
        );

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
     * 逆順で返すイテレータを返します。
     *
     * @return \Traversable イテレータ
     */
    public function getIteratorReversed(): \Traversable
    {
        return function (): \Generator {
            foreach (\array_reverse(\array_keys($this->collection)) as $key) {
                yield $this->collection[$key];
            }
        };
    }

    /**
     * ユニークキーでソートしたイテレータを返します。
     *
     * @return \Traversable イテレータ
     */
    public function getIteratorSortedByUniqueKey(bool $descending = false): \Traversable
    {
        $collection = $this->collection;

        $descending ? \krsort($collection) : \ksort($collection);

        return new \ArrayIterator($collection);
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
     * コレクションのJSONにシリアライズするための表現を返します。
     *
     * @return mixed コレクションのJSONにシリアライズするための表現
     * @see https://www.php.net/manual/ja/class.jsonserializable.php
     */
    public function jsonSerialize(): mixed
    {
        if ($this->enabledJsonSerializer) {
            return ($this->jsonSerializer)($this);
        }

        return $this->collection;
    }

    /**
     * 受け入れるクラスが持つパブリックメソッドのリストを返します。
     *
     * @return array 受け入れるクラスが持つパブリックメソッドのリスト
     */
    protected function getObjectMethodList(): array
    {
        if (empty($this->objectAccessPointList)) {
            $object_method_list    = [];

            foreach ((new \ReflectionClass(static::getAllowedClass(9)))->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                $method_name    = $method->getName();

                if (!\str_starts_with($method_name, 'get')) {
                    continue;
                }

                $object_method_list[$method->getName()]    = [
                    'map_key'   => $map_key = \mb_substr($method_name, 4),
                    'length'    => \mb_strlen($map_key),
                ];
            }

            \uksort($object_method_list, function($a, $b): int {
                return \strlen($b) <=> \strlen($a) ?: \strnatcmp($b, $a);
            });

            $this->objectAccessPointList    = $object_method_list;
        }

        return $this->objectAccessPointList;
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
     * @param  object $object        オブジェクト
     * @return array  キャッシュ用検索条件
     */
    protected function createCriteriaForCache(array $criteria_keys, object $object): array
    {
        $criteria   = [];

        $keyAccessType    = $this->getKeyAccessType();

        foreach ($criteria_keys as $map_key) {
            \array_key_exists($map_key, $this->accessKeyCache) ?: $this->setAccessKeyCache($map_key, $keyAccessType);

            $criteria[$map_key] = static::normalizeKey(
                match ($keyAccessType->name) {
                    KeyAccessTypeEnum::Property->name     => $object->{$this->accessKeyCache[$map_key]},
                    KeyAccessTypeEnum::ArrayAccess->name  => $object[$this->accessKeyCache[$map_key]],
                    default                               => $object->{$this->accessKeyCache[$map_key]}(),
                },
                $this->accessKeyCache[$map_key],
            );
        }

        return $criteria;
    }

    /**
     * キャッシュをセットします。
     *
     * @param string $cache_key キャッシュキー
     * @param object $object    キャッシュに設定するオブジェクト
     * @param array  $criteria  検索条件
     */
    protected function setCache(
        string $cache_key,
        object $object,
        array $criteria,
    ): static {
        $in_nest_list   = [];

        $criteria_keys  = [];

        $unique_id = static::extractUniqueId($object);

        $keyAccessType    = $this->getKeyAccessType();

        foreach ($criteria as $map_key => $value) {
            $criteria_keys[]    = $map_key;

            \array_key_exists($map_key, $this->accessKeyCache) ?: $this->setAccessKeyCache($map_key, $keyAccessType);

            $in_nest_list[] = static::normalizeKey(
                match ($keyAccessType->name) {
                    KeyAccessTypeEnum::Property->name     => $object->{$this->accessKeyCache[$map_key]},
                    KeyAccessTypeEnum::ArrayAccess->name  => $object[$this->accessKeyCache[$map_key]],
                    default                               => $object->{$this->accessKeyCache[$map_key]}(),
                },
                $this->accessKeyCache[$map_key],
            );
        }

        $this->reverseCacheMap[$unique_id][$cache_key] = $criteria_keys;

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
                if ($uk === $unique_id) {
                    unset($tmp[$target_key][$idx]);
                }
            }

            $tmp[$target_key][] = $unique_id;
        } else {
            $tmp[$target_key] = [$unique_id];
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

            foreach ($this->collection as $object) {
                $this->setCache($cache_key, $object, $criteria);
            }
        }

        return $this->cacheMap[$cache_key];
    }

    /**
     * アクセスキーキャッシュを設定します。
     *
     * @param  string $map_key マップキー
     * @return static このインスタンス
     */
    protected function setAccessKeyCache(string $map_key, ?KeyAccessTypeEnum $keyAccessType = null): static
    {
        $this->accessKeyCache[$map_key] = match (($keyAccessType ?? $this->getKeyAccessType())->name) {
            KeyAccessTypeEnum::Property->name     => \lcfirst(\strtr(\ucwords(\strtr($map_key, ['_' => ' '])), [' ' => ''])),
            KeyAccessTypeEnum::ArrayAccess->name  => $map_key,
            default                               => 'get' . \ucfirst(\strtr(\ucwords(\strtr($map_key, ['_' => ' '])), [' ' => ''])),
        };

        return $this;
    }

    /**
     * キャッシュマップの末端配列を末端配列第一マップキーに紐づくオブジェクトに置き換えます。
     *
     * @param  array           $cache_map キャッシュマップ
     * @return int|string|bool 末端の値
     */
    protected function replaceCahceMapToOne(array &$cache_map): int|string|bool
    {
        foreach ($cache_map as $key => &$value) {
            if (\is_array($value)) {
                if (!\is_bool($key = $this->replaceCahceMapToOne($value))) {
                    $value = $this->collection[$key];
                }
            } else {
                return $value;
            }
        }

        return false;
    }

    /**
     * キャッシュマップの末端配列を末端配列第一マップキーに配列に置き換えます。
     *
     * @param  array             $cache_map     キャッシュマップ
     * @param  array             $map_keys      マップキー
     * @param  KeyAccessTypeEnum $keyAccessType オブジェクトの値の取得の仕方
     * @return int|string|bool   末端の値
     */
    protected function replaceCahceMapToArrayOne(
        array &$cache_map,
        array $map_keys,
        KeyAccessTypeEnum $keyAccessType,
    ): int|string|bool {
        foreach ($cache_map as $key => &$value) {
            if (\is_array($value)) {
                if (!\is_bool($key = $this->replaceCahceMapToArrayOne($value, $map_keys, $keyAccessType))) {
                    $map    = [];

                    foreach ($map_keys as $map_key) {
                        $access_key     = $this->accessKeyCache[$map_key];

                        $map[$map_key]  = match ($keyAccessType->name) {
                            KeyAccessTypeEnum::Property->name     => $this->collection[$key]->{$access_key},
                            KeyAccessTypeEnum::ArrayAccess->name  => $this->collection[$key][$access_key],
                            default                               => $this->collection[$key]->{$access_key}(),
                        };
                    }

                    $value  = $map;
                }
            } else {
                return $value;
            }
        }

        return false;
    }

    /**
     * キャッシュマップの末端配列を末端配列第一マップキーに配列に置き換えます。
     *
     * @param  array             $cache_map     キャッシュマップ
     * @param  KeyAccessTypeEnum $keyAccessType オブジェクトの値の取得の仕方
     * @return int|string|bool   末端の値
     */
    protected function replaceCahceMapGetArrayOne(
        array &$cache_map,
        int|string|callable $target,
        KeyAccessTypeEnum $keyAccessType,
    ): int|string|bool {
        foreach ($cache_map as &$value) {
            if (\is_array($value)) {
                if (!\is_bool($key = $this->replaceCahceMapGetArrayOne($value, $target, $keyAccessType))) {
                    if (\is_callable($target)) {
                        $value  = $target(
                            $this->collection[$key],
                            $this->accessKeyCache,
                        );
                    } else {
                        $access_key = $this->accessKeyCache[$target];

                        $value  = match ($keyAccessType->name) {
                            KeyAccessTypeEnum::Property->name     => $this->collection[$key]->{$access_key},
                            KeyAccessTypeEnum::ArrayAccess->name  => $this->collection[$key][$access_key],
                            default                               => $this->collection[$key]->{$access_key}(),
                        };
                    }
                }
            } else {
                return $value;
            }
        }

        return false;
    }
}
