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

use tacddd\value_objects\lang_types\php\traits\adjusters\PhpEnumAdjusterTrait;

/**
 * 言語型：PHP：enum：factory method
 */
trait PhpEnumFactoryMethodTrait
{
    use PhpEnumAdjusterTrait;

    /**
     * factory
     *
     * @param  self|object $value    値
     * @param  bool        $autoload 値の検証時にオートロードするかどうか
     * @return static      このインスタンス
     */
    public static function of(object $value, bool $autoload = true): static
    {
        if ($value instanceof self) {
            return $value;
        }

        return new static(static::adjust($value), $autoload);
    }
}
