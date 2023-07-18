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

namespace tacddd\value_objects\lang_types\php\traits\adjusters;

/**
 * 言語型：PHP：int：adjuster method
 */
trait PhpIntAdjusterTrait
{
    /**
     * adjust
     *
     * @param  string|int|float $value 値
     * @return int              調整済みの値
     */
    public static function adjust(string|int|float $value): int
    {
        if (\is_int($value)) {
            return $value;
        }

        if (\is_float($value)) {
            return (int) $value;
        }

        return \filter_var($value, \FILTER_VALIDATE_INT, [
            'options'   => [
                'min_range' => \PHP_INT_MIN,
                'max_range' => \PHP_INT_MAX,
            ],
            'flags' => \FILTER_FLAG_ALLOW_OCTAL | \FILTER_FLAG_ALLOW_HEX | \FILTER_NULL_ON_FAILURE,
        ]) ?? throw new \TypeError(\sprintf('整数に利用できない文字列が指定されました。value:"%s"', $value));
    }
}
