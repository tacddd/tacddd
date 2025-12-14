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
 * @version     1.0.0
 */

declare(strict_types=1);

namespace tacddd\utilities\containers\services\traits;

use Psr\Container\ContainerInterface as PsrContainerInterface;

/**
 * 簡易コンテナインターフェース
 */
interface ContainerInterface extends PsrContainerInterface
{
    /**
     * オブジェクトを設定します。
     *
     * @param  string        $id          ID
     * @param  string|object $object      オブジェクト
     * @param  bool          $shared      共有するかどうか
     * @param  bool          $only_create 逐次生成に限定するかどうか
     * @param  null|array    $parameters  コンストラクト時引数
     * @return static        このインスタンス
     */
    public function set(string $id, string|object $object, bool $shared = false, bool $only_create = false, array $parameters = []): static;

    /**
     * IDに紐づくオブジェクトがあるかどうかを返します
     *
     * @param  string $id ID
     * @return bool   IDに紐づくオブジェクトがあるかどうか
     */
    public function has(string $id): bool;

    /**
     * オブジェクトを取得します。
     *
     * @param  string $id ID
     * @return object オブジェクト
     */
    public function get(string $id): object;

    /**
     * オブジェクトを構築し返します。
     *
     * @param  string $id            ID
     * @param  mixed  ...$parameters 構築時引数
     * @return object オブジェクト
     */
    public function create(string $id, mixed ...$parameters): object;
}
