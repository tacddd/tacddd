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
 * エンティティコレクション特性インターフェース
 */
interface EntityCollectionInterface extends
    \IteratorAggregate,
    \Countable
{
    /**
     * 指定されたエンティティからユニークIDを返します。
     *
     * @param  object     $entity エンティティ
     * @return string|int ユニークID
     */
    public static function createUniqueId(object $entity): string|int;

    /**
     * 与えられたエンティティからユニークIDを抽出して返します。
     *
     * @param  object     $entity エンティティ
     * @return string|int ユニークID
     */
    public static function extractUniqueId(object $entity): string|int;

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
     * キーがstring|intではなかった場合に調整して返します。
     *
     * @param  mixed       $key         キー
     * @param  null|string $method_name メソッド名
     * @return string|int  調整済みキー
     */
    public static function adjustKey(mixed $key, ?string $method_name = null): string|int;

    /**
     * constructor
     *
     * @param iterable $entities 初期状態として受け入れるエンティティの配列
     * @param array    $options  オプション
     */
    public function __construct(iterable $entities = [], array $options = []);

    /**
     * エンティティを追加します。
     *
     * @param  object $entity エンティティ
     * @return static このインスタンス
     */
    public function add(object $entity): static;

    /**
     * エンティティを纏めて追加します。
     *
     * @param  iterable $entities エンティティ
     * @return static   このインスタンス
     */
    public function addAll(iterable $entities): static;

    /**
     * エンティティがコレクションに含まれているかどうかを返します。
     *
     * @param  object $entity 検索対象
     * @return bool   エンティティが存在するかどうか
     */
    public function contains(object $entity): bool;

    /**
     * 指定したエンティティが全てコレクションに含まれているかどうかを返します。
     *
     * @param  iterable $entities 検索対象
     * @return bool     エンティティが存在するかどうか
     */
    public function containsAll(iterable $entities): bool;

    /**
     * 指定したエンティティの何れかがコレクションに含まれているかどうかを返します。
     *
     * @param  iterable $entities 検索対象
     * @return bool     エンティティが存在するかどうか
     */
    public function containsAny(iterable $entities): bool;

    /**
     * 指定した検索条件のエンティティが存在するかどうかを返します。
     *
     * @param  array $criteria 検索条件
     * @return bool  エンティティが存在するかどうか
     */
    public function hasBy(array $criteria): bool;

    /**
     * 指定したキーのエンティティを探して返します。
     *
     * @param  int|string|object $key ユニークID
     * @return null|object       エンティティ
     */
    public function find(int|string|object $key): ?object;

    /**
     * コレクションの全エンティティを返します。
     *
     * @return array コレクションの全エンティティ
     */
    public function findAll(): array;

    /**
     * 指定したキーのエンティティを探して返します。
     *
     * @param  array    $criteria 検索条件
     * @param  array    $order_by ソート設定
     * @return object[] 検索結果
     */
    public function findBy(array $criteria, array $order_by = []): array;

    /**
     * 指定したキーのエンティティを探して返します。
     *
     * @param  array  $criteria 検索条件
     * @param  array  $order_by ソート設定
     * @return object 検索結果
     */
    public function findOneBy(array $criteria, array $order_by = []): ?object;

    /**
     * 指定したキーのエンティティを探してマップにして返します。
     *
     * @param  array    $criteria 検索条件
     * @param  array    $map_keys マップキー
     * @param  array    $order_by ソート設定
     * @return object[] 検索結果
     */
    public function findToMapBy(array $criteria, array $map_keys = [], array $order_by = []): array;

    /**
     * エンティティを取り外します。
     *
     * @param  object $entity エンティティ
     * @return static このインスタンス
     */
    public function remove(object $entity): static;

    /**
     * 指定したキーのエンティティを取り外します。
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
