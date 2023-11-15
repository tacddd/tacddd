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

namespace tacddd\utilities\containers\value_objects\traits\adapters;

use tacddd\utilities\containers\value_objects\traits\accessors\ContainerAccessorInterface;

/**
 * コンテナアダプタ特性
 */
trait ContainerAdapterTrait
{
    /**
     * constructor
     *
     * @param object                     $container         コンテナインスタンス
     * @param ContainerAccessorInterface $containerAccessor コンテナアクセサ
     * @param iterable デフォルト設定
     */
    public function __construct(
        public readonly object $container,
        public readonly ContainerAccessorInterface $containerAccessor,
        iterable $default_settings = [],
    ) {
        foreach ($default_settings as $default_setting) {
            $this->set(...$default_setting);
        }
    }

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
    public function set(string $id, string|object $object, bool $shared = false, bool $only_create = false, array $parameters = []): static
    {
        $this->containerAccessor->set($this->container, $id, $object, $shared, $only_create, $parameters);

        return $this;
    }

    /**
     * IDに紐づくオブジェクトがあるかどうかを返します
     *
     * @param  string $id ID
     * @return bool   IDに紐づくオブジェクトがあるかどうか
     */
    public function has(string $id): bool
    {
        return $this->containerAccessor->has($this->container, $id);
    }

    /**
     * オブジェクトを取得します。
     *
     * @param  string $id ID
     * @return object オブジェクト
     */
    public function get(string $id): object
    {
        return $this->containerAccessor->get($this->container, $id);
    }

    /**
     * オブジェクトを構築し返します。
     *
     * @param  string $id            ID
     * @param  mixed  ...$parameters 構築時引数
     * @return object オブジェクト
     */
    public function create(string $id, mixed ...$parameters): object
    {
        return $this->containerAccessor->create($this->container, $id, ...$parameters);
    }
}
