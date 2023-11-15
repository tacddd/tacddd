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

namespace tacddd\utilities\containers\value_objects\traits\accessors;

use Psr\Container\ContainerInterface as PsrContainerInterface;
use tacddd\utilities\containers\services\traits\ContainerInterface;

/**
 * コンテナアクセサ特性
 */
trait ContainerAccessorTrait
{
    /**
     * オブジェクトを設定します。
     *
     * @param  object        $container   コンテナ実体
     * @param  string        $id          ID
     * @param  string|object $object      オブジェクト
     * @param  bool          $shared      共有するかどうか
     * @param  bool          $only_create 逐次生成に限定するかどうか
     * @param  null|array    $parameters  コンストラクト時引数
     * @return static        このインスタンス
     */
    public function set(object $container, string $id, string|object $object, bool $shared = false, bool $only_create = false, array $parameters = []): static
    {
        /** @var ContainerInterface|PsrContainerInterface $container */
        $container->set($id, $object, $shared, $only_create, $parameters);

        return $this;
    }

    /**
     * IDに紐づくオブジェクトがあるかどうかを返します
     *
     * @param  string $id ID
     * @return bool   IDに紐づくオブジェクトがあるかどうか
     */
    public function has(object $container, string $id): bool
    {
        /** @var ContainerInterface|PsrContainerInterface $container */
        return $container->has($id);
    }

    /**
     * オブジェクトを取得します。
     *
     * @param  string $id ID
     * @return object オブジェクト
     */
    public function get(object $container, string $id): object
    {
        /** @var ContainerInterface|PsrContainerInterface $container */
        return $container->get($id);
    }

    /**
     * オブジェクトを構築し返します。
     *
     * @param  object $container     コンテナ実体
     * @param  string $id            ID
     * @param  mixed  ...$parameters 構築時引数
     * @return object オブジェクト
     */
    public function create(object $container, string $id, mixed ...$parameters): object
    {
        /** @var ContainerInterface|PsrContainerInterface $container */
        return $container->create($id, ...$parameters);
    }
}
