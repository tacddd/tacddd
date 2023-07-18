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

namespace tacddd\value_objects\lang_types\php\abstracts;

use tacddd\value_objects\traits\array_access\ArrayAccessInterface;
use tacddd\value_objects\traits\array_access\ArrayAccessTrait;

/**
 * 抽象言語型：PHP：array list
 */
abstract readonly class AbstractPhpList implements ArrayAccessInterface
{
    use ArrayAccessTrait;

    /**
     * constructor
     */
    public function __construct(public array $value)
    {
        if (!\array_is_list($value)) {
            throw new \ValueError('リストではない配列を指定されました。');
        }
    }
}
