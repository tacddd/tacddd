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

namespace tacddd\value_objects\figures\positive_ints\traits;

/**
 * スペック特性：正の整数
 */
trait PositiveIntSpecTrait
{
    /**
     * 受け付ける最小値を返します。
     *
     * @return integer
     */
    public static function getMin(): int
    {
        return 1;
    }

    /**
     * 受け付ける最大値を返します。
     *
     * @return int 受け付ける最大値
     */
    public static function getMax(): int
    {
        return \PHP_INT_MAX;
    }
}
