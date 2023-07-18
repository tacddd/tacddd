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
 * 言語型：PHP：nullable bool：adjuster method
 */
trait PhpNullableBoolAdjusterTrait
{
    /**
     * adjust
     *
     * @param  null|string|int|float|bool $value 値
     * @return null|bool                  調整済みの値
     */
    public static function adjust(null|string|int|float|bool $value): ?bool
    {
        if ($value === null) {
            return $value;
        }

        if (\is_bool($value)) {
            return $value;
        }

        return \filter_var($value, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE)
         ?? throw new \TypeError(\sprintf('真偽値として利用できない値が指定されました。value:"%s"', $value));
    }
}
