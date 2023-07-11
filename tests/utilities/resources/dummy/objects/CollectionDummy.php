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

namespace tacddd\tests\utilities\resources\dummy\objects;

use tacddd\collections\traits\objects\ObjectCollectionInterface;
use tacddd\collections\traits\objects\ObjectCollectionTrait;

/**
 * @method CollectionElementDummy getById(int $id)
 */
final class CollectionDummy implements ObjectCollectionInterface
{
    use ObjectCollectionTrait;

    public static function getAllowedClasses(): string|array
    {
        return CollectionElementDummy::class;
    }

    public static function createUniqueKey(object $element): string|int
    {
        return $element->getId();
    }
}
