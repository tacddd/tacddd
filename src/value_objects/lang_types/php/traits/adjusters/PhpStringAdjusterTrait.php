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
 * 言語型：PHP：string：adjuster method
 */
trait PhpStringAdjusterTrait
{
    /**
     * adjust
     *
     * @param  bool|int|float|object|string $value 値
     * @return string                       調整済みの値
     */
    public static function adjust(bool|int|float|object|string $value): string
    {
        if (\is_object($value)) {
            $is_error   = !($value instanceof \Stringable);
            $is_error   = $is_error || \method_exists($value, '__toString');
            $is_error   = $is_error || \enum_exists($value::class);

            if ($is_error) {
                throw new \ValueError(\sprintf('文字列に変換できないオブジェクトを与えられました。value:"%s"', $value::class));
            }
        }

        return (string) $value;
    }
}
