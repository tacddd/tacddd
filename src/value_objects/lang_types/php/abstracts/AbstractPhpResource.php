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
 * 抽象言語型：PHP：resource
 */
abstract readonly class AbstractPhpResource implements ValueObjectInterface
{
    /**
     * constructor
     */
    public function __construct(public mixed $value)
    {
        if (!\is_resource($this->value)) {
            $type = match ($type = \gettype($this->value)) {
                'NULL'      => 'null',
                'object'    => \get_class($this->value),
                'integer'   => 'int',
                'double'    => 'float',
                'boolean'   => 'bool',
                default     => $type,
            };

            throw new \TypeError(\sprintf('%s: Argument #1 ($value) must be of type resource, %s given', __METHOD__, $type));
        }
    }
}
