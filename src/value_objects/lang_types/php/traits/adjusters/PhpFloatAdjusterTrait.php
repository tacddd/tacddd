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
 * 言語型：PHP：float：adjuster method
 */
trait PhpFloatAdjusterTrait
{
    /**
     * adjust
     *
     * @param  string|int|float $value 値
     * @return float            調整済みの値
     */
    public static function adjust(string|int|float $value): float
    {
        if (\is_float($value)) {
            return $value;
        }

        if (\is_int($value)) {
            return (float) $value;
        }

        return \filter_var($value, \FILTER_VALIDATE_FLOAT, [
            'options'   => [
                'min_range' => -\PHP_FLOAT_MAX,
                'max_range' => \PHP_FLOAT_MAX,
            ],
            'flags' => \FILTER_FLAG_ALLOW_THOUSAND | \FILTER_NULL_ON_FAILURE,
        ]) ?? throw new \TypeError(\sprintf('浮動小数点に利用できない文字列が指定されました。value:"%s"', $value));
    }
}
