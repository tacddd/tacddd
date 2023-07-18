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

use tacddd\value_objects\interfaces\ValueObjectInterface;

/**
 * 抽象言語型：PHP：object
 */
abstract readonly class AbstractPhpObject implements ValueObjectInterface
{
    /**
     * constructor
     */
    public function __construct(public object $value)
    {
        if (\enum_exists($this->value::class)) {
            throw new \ValueError(\sprintf('列挙型を与えられました。value:"%s"', $this->value::class));
        }
    }
}
