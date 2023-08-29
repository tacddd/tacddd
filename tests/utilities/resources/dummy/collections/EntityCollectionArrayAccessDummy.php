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

namespace tacddd\tests\utilities\resources\dummy\collections;

use tacddd\collections\objects\traits\ObjectCollectionInterface;
use tacddd\collections\objects\traits\ObjectCollectionTrait;
use tacddd\tests\utilities\resources\dummy\objects\EntityCollectionEntityDummy;

final class EntityCollectionArrayAccessDummy implements ObjectCollectionInterface
{
    use ObjectCollectionTrait;

    public static function getAllowedClass(): string
    {
        return EntityCollectionEntityDummy::class;
    }

    public static function createUniqueId(mixed $value): string|int
    {
        return $value->getId()->value;
    }

    public static function adjustKey(mixed $key, ?string $access_key = null): string|int
    {
        return \is_object($key) ? $key->value : $key;
    }
}
