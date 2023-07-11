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

use tacddd\collections\traits\objects\magical_accesser\ObjectCollectionMagicalAccessorInterface;

/**
 * コレクション特性
 */
interface ObjectCollectionInterface extends \IteratorAggregate, ObjectCollectionMagicalAccessorInterface
{
    /**
     * constructor
     *
     * @param iterable $elements 初期状態として受け入れるオブジェクトの配列
     * @param array    $options  オプション
     */
    public function __construct(iterable $elements = [], array $options = []);

    /**
     * オブジェクトを追加します。
     *
     * @param  object $element オブジェクト
     * @return static このインスタンス
     */
    public function add(object $element): static;

    /**
     * オブジェクトが存在するかどうかを返します。
     *
     * @param  int|string $key ユニークキー
     * @return bool       オブジェクトが存在するかどうか
     */
    public function has(int|string $key): bool;

    /**
     * オブジェクトを返します。
     *
     * @param  int|string  $key ユニークキー
     * @return null|object オブジェクト
     */
    public function get(int|string $key): ?object;

    /**
     * オブジェクトを設定します。
     *
     * @param object オブジェクト
     * @return static このインスタンス
     */
    public function set(object $element): static;

    /**
     * コレクションの最初の要素を返します。
     *
     * @return object コレクションの最初の要素
     */
    public function first(): ?object;

    /**
     * コレクションの最後の要素を返します。
     *
     * @return object コレクションの最後の要素
     */
    public function last(): ?object;

    /**
     * イテレータを返します。
     *
     * @return \Traversable イテレータ
     */
    public function getIterator(): \Traversable;

    /**
     * コレクションの配列表現を返します。
     *
     * @return array コレクションの配列表現
     */
    public function toArray(): array;
}
