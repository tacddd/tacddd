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

namespace tacddd\value_objects\bools\traits;

use tacddd\utilities\containers\ContainerService;

/**
 * fromString特性：真偽値
 */
trait BoolFromStringTrait
{
    /**
     * 文字列からオブジェクトを生成します。
     *
     * @param  string $value 文字列
     * @return static オブジェクト
     */
    public static function fromString(string $value): static
    {
        return new static(
            \filter_var($value, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE)
             ?? throw new \TypeError(ContainerService::getStringService()->buildDebugMessage('真偽値として利用できない値が指定されました。', $value)),
        );
    }
}
