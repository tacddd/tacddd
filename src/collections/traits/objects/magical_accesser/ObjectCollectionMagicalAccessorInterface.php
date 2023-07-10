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

namespace tacd\collections\traits\objects\magical_accesser;

/**
 * オブジェクトコレクションマジックアクセス特性
 */
interface ObjectCollectionMagicalAccessorInterface
{
    /**
     * Magical method
     *
     * @param  string $method_name メソッド名
     * @param  array  $arguments   引数
     * @return mixed  返り値
     */
    public function __call(string $method_name, array $arguments): mixed;
}
