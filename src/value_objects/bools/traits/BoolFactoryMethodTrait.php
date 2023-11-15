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

namespace tacddd\value_objects\bools\traits;

use tacddd\utilities\containers\ContainerService;

/**
 * ファクトリメソッド特性：真偽値
 */
trait BoolFactoryMethodTrait
{
    /**
     * factory
     *
     * @return self|string|int|float|bool $value 変換可能な値からオブジェクトを生成します
     * @return static                     このインスタンス
     */
    public static function of(self|string|int|float|bool $value): static
    {
        if ($value instanceof self) {
            return $value;
        }

        if (!\is_bool($value)) {
            $value  = \filter_var($value, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE)
             ?? throw new \TypeError(ContainerService::getStringService()->buildDebugMessage('真偽値として利用できない値が指定されました。', $value));
        }

        return ContainerService::getValueObjectCacheService()->cacheableFromString(static::class, $value ? 'true' : 'false');
    }
}
