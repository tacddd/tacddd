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

namespace tacddd\value_objects\lang_types\php\traits\factory_methods;

use tacddd\value_objects\lang_types\php\traits\adjusters\PhpNullableStringAdjusterTrait;

/**
 * 言語型：PHP：nullable string：factory method
 */
trait PhpNullableStringFactoryMethodTrait
{
    use PhpNullableStringAdjusterTrait;

    /**
     * factory
     *
     * @param  null|bool|int|float|object|string $value 値
     * @return static                            このインスタンス
     */
    public static function of(null|bool|int|float|object|string $value): static
    {
        if ($value instanceof self) {
            return $value;
        }

        if ($value instanceof static) {
            return $value;
        }

        return new static(static::adjust($value));
    }
}
