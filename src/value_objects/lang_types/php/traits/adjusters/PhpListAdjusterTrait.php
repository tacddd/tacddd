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
 * 言語型：PHP：list：adjuster method
 */
trait PhpListAdjusterTrait
{
    /**
     * adjust
     *
     * @param  self|\IteratorAggregate|\Iterator|array $value 値
     * @return array                                   調整済みの値
     */
    public static function adjust(\IteratorAggregate|\Iterator|array $value): array
    {
        if ($value instanceof \IteratorAggregate) {
            $value = $value->getIterator();
        }

        if ($value instanceof \Iterator) {
            $value = \iterator_to_array($value);
        }

        return $value;
    }
}
