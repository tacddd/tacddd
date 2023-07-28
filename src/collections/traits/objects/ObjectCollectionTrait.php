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

namespace tacddd\collections\traits\objects;

use tacddd\collections\traits\objects\magical_accesser\ObjectCollectionMagicalAccessorTrait;

/**
 * オブジェクトコレクション特性
 */
trait ObjectCollectionTrait
{
    use ObjectCollectionMagicalAccessorTrait;

    /**
     * @return array オプション
     */
    protected array $options;

    /**
     * constructor
     *
     * @param iterable $elements 初期状態として受け入れるオブジェクトの配列
     * @param array    $options  オプション
     */
    public function __construct(iterable $elements = [], array $options = [])
    {
        $this->options  = $options;

        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * オブジェクトを追加します。
     *
     * @param  object $element オブジェクト
     * @return static このインスタンス
     */
    public function add(object $element): static
    {
        if (!static::isAllowedClass($element)) {
            throw new \TypeError(\sprintf('受け入れ可能外のクラスを指定されました。class:%s, allowed_classes:%s', $element::class, \implode(', ', (array) static::getAllowedClasses())));
        }

        $unique_key = static::createUniqueKey($element);

        if (!\is_string($unique_key) && !\is_int($unique_key)) {
            $unique_key = static::adjustKey($unique_key);
        }

        $this->collection[$unique_key] = $element;

        return $this;
    }

    /**
     * オブジェクトが存在するかどうかを返します。
     *
     * @param  int|string $key ユニークキー
     * @return bool       オブジェクトが存在するかどうか
     */
    public function has(int|string $key): bool
    {
        return \array_key_exists($key, $this->collection);
    }

    /**
     * オブジェクトを返します。
     *
     * @param  int|string  $key ユニークキー
     * @return null|object オブジェクト
     */
    public function get(int|string $key): ?object
    {
        return $this->collection[$key] ?? null;
    }

    /**
     * オブジェクトを設定します。
     *
     * @param object オブジェクト
     * @return static このインスタンス
     */
    public function set(object $element): static
    {
        return $this->add($element);
    }

    /**
     * オブジェクトを取り外します。
     *
     * @param  int|string $key ユニークキー
     * @return static     このインスタンス
     */
    public function remove(int|string|object $key): static
    {
        if (\is_object($key)) {
            if (!static::isAllowedClass($key)) {
                throw new \TypeError(\sprintf('受け入れ不能なクラスオブジェクトを指定されました。class:%s', $key::class));
            }

            $key = static::createUniqueKey($key);

            if (!\is_string($key) && !\is_int($key)) {
                $key = static::adjustKey($key);
            }
        }

        unset($this->collection[$key]);

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
}
