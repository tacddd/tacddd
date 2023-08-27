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

use tacddd\collections\entities\traits\EntityCollectionInterface;
use tacddd\collections\entities\traits\EntityCollectionTrait;

abstract class AbstractCollectionDummy implements EntityCollectionInterface
{
    use EntityCollectionTrait;

    public static function getAllowedClass(): string
    {
        return CollectionEntityDummy::class;
    }

    public static function createUniqueId(object $element): string|int
    {
        return $element->getId();
    }

    public static function adjustKey(mixed $key, ?string $access_key = null): string|int
    {
        $allowed_class  = static::getAllowedClass();

        if ($key instanceof $allowed_class) {
            return $key->{'get' . $access_key}();
        }

        if (\is_object($key)) {
            return $key->value;
        }

        return $key;
    }
}
