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

use tacddd\collections\CollectionInterface;

/**
 * オブジェクトコレクション特性インターフェース
 */
interface ObjectCollectionInterface extends CollectionInterface
{
    /**
     * 与えられたオブジェクトからユニークIDを抽出して返します。
     *
     * @param  object     $object オブジェクト
     * @return string|int ユニークID
     */
    public static function extractUniqueId(object $object): string|int;

    /**
     * 受け入れ可能なクラスを返します。
     *
     * @return string 受け入れ可能なクラス
     */
    public static function getAllowedClass(): string;

    /**
     * 受け入れ可能なクラスかどうかを返します。
     *
     * @param  string $class クラスパス
     * @return bool   受け入れ可能なクラスかどうか
     */
    public static function isAllowedClass(object|string $class): bool;

    /**
     * constructor
     *
     * @param iterable $objects 初期状態として受け入れるオブジェクトの配列
     * @param array    $options オプション
     */
    public function __construct(iterable $objects = [], array $options = []);

    /**
     * オブジェクトを追加します。
     *
     * @param  object $object オブジェクト
     * @return static このインスタンス
     */
    public function add(object $object): static;

    /**
     * オブジェクトを纏めて追加します。
     *
     * @param  iterable $objects オブジェクト
     * @return static   このインスタンス
     */
    public function addAll(iterable $objects): static;

    /**
     * オブジェクトがコレクションに含まれているかどうかを返します。
     *
     * @param  object $object 検索対象
     * @return bool   オブジェクトが存在するかどうか
     */
    public function contains(object $object): bool;

    /**
     * 指定したオブジェクトが全てコレクションに含まれているかどうかを返します。
     *
     * @param  iterable $objects 検索対象
     * @return bool     オブジェクトが存在するかどうか
     */
    public function containsAll(iterable $objects): bool;

    /**
     * 指定したオブジェクトの何れかがコレクションに含まれているかどうかを返します。
     *
     * @param  iterable $objects 検索対象
     * @return bool     オブジェクトが存在するかどうか
     */
    public function containsAny(iterable $objects): bool;

    /**
     * 指定したキーのオブジェクトを探して返します。
     *
     * @param  int|string|object $key ユニークID
     * @return null|object       オブジェクト
     */
    public function find(int|string|object $key): ?object;

    /**
     * オブジェクトを取り外します。
     *
     * @param  object $object オブジェクト
     * @return static このインスタンス
     */
    public function remove(object $object): static;

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
}
