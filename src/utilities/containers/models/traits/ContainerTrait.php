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

namespace tacddd\utilities\containers\models\traits;

use tacddd\utilities\containers\ContainerService;
use tacddd\utilities\containers\exceptions\NotFoundException;

/**
 * 簡易コンテナ特性
 */
trait ContainerTrait
{
    /**
     * @var array 共有オブジェクトキャッシュ
     */
    private array $sharedObjectCache = [];

    /**
     * @var array コンテナ設定
     */
    private array $configs  = [];

    /**
     * オブジェクトを設定します。
     *
     * @param  string        $id         ID
     * @param  string|object $object     オブジェクト
     * @param  bool          $shared     共有するかどうか
     * @param  null|array    $parameters コンストラクト時引数
     * @return static        このインスタンス
     */
    public function set(string $id, string|object $object, bool $shared = false, array $parameters = []): static
    {
        $this->configs[$id]  = [
            'object'        => $object,
            'shared'        => $shared,
            'parameters'    => $parameters,
        ];

        unset($this->sharedObjectCache[$id]);

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
        return \array_key_exists($id, $this->configs);
    }

    /**
     * オブジェクトを取得します。
     *
     * @param  string            $id ID
     * @throws NotFoundException IDに紐づく設定が無い場合
     * @return object            オブジェクト
     */
    public function get(string $id): object
    {
        if (!\array_key_exists($id, $this->configs)) {
            throw new NotFoundException(ContainerService::getStringService()->buildDebugMessage('指定されたIDに紐づく設定がありません。', $id));
        }

        $shared     = $this->configs[$id]['shared'];
        $is_cached  = \array_key_exists($id, $this->sharedObjectCache);

        if (!$shared || !$is_cached) {
            $object     = $this->configs[$id]['object'];
            $parameters = $this->configs[$id]['parameters'] ?? [];

            if ($object instanceof \Closure) {
                $object = $object(...$parameters);
            }

            if (\is_string($object)) {
                $instance   = new $object(...$parameters);
            } else {
                $instance   = $object;
            }

            if ($shared) {
                $this->sharedObjectCache[$id] = $instance;
            }
        } else {
            $instance   = $this->sharedObjectCache[$id];
        }

        return $instance;
    }
}
