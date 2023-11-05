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

namespace tacddd\collections;

/**
 * コレクションインターフェース
 */
interface CollectionInterface extends
    \IteratorAggregate,
    \Countable
{
    /**
     * 指定された値からユニークIDを返します。
     *
     * @param  mixed      $value 値
     * @return int|string ユニークID
     */
    public static function createUniqueId(mixed $value): string|int;

    /**
     * キーがstring|intではなかった場合に調整して返します。
     *
     * @param  mixed       $key        キー
     * @param  null|string $access_key アクセスキー
     * @return string|int  調整済みキー
     */
    public static function normalizeKey(mixed $key, ?string $access_key = null): string|int;

    /**
     * 指定した検索条件のオブジェクトが存在するかどうかを返します。
     *
     * @param  array $criteria 検索条件
     * @return bool  オブジェクトが存在するかどうか
     */
    public function hasBy(array $criteria): bool;

    /**
     * コレクションの全オブジェクトを返します。
     *
     * @return array コレクションの全オブジェクト
     */
    public function findAll(): array;

    /**
     * 指定したキーのオブジェクトを探して返します。
     *
     * @param  array    $criteria 検索条件
     * @param  array    $order_by ソート設定
     * @return object[] 検索結果
     */
    public function findBy(array $criteria, array $order_by = []): array;

    /**
     * 指定したキーのオブジェクトを探して返します。
     *
     * @param  array  $criteria 検索条件
     * @param  array  $order_by ソート設定
     * @return object 検索結果
     */
    public function findOneBy(array $criteria, array $order_by = []): ?object;

    /**
     * 指定したキーのオブジェクトを探してマップにして返します。
     *
     * @param  array    $criteria 検索条件
     * @param  array    $map_keys マップキー
     * @param  array    $order_by ソート設定
     * @return object[] 検索結果
     */
    public function findToMapBy(array $criteria, array $map_keys = [], array $order_by = []): array;

    /**
     * 指定したキーのオブジェクトを取り外します。
     *
     * @param  array  $criteria 検索条件
     * @return static このインスタンス
     */
    public function removeBy(array $criteria): static;

    /**
     * このコレクションをクリアします。
     *
     * @return static このインスタンス
     */
    public function clear(): static;

    /**
     * 現在のコレクション数を返します。
     *
     * @return int 現在のコレクション数
     */
    public function count(): int;

    /**
     * 現在のコレクションが空かどうか返します。
     *
     * @return bool 現在のコレクションが空かどうか
     */
    public function empty(): bool;

    /**
     * コレクションを指定したキーの階層構造を持つマップに変換して返します。
     *
     * @return array コレクションマップ
     */
    public function toMap(array $map_keys): array;

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
