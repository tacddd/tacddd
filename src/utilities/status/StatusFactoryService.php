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

namespace tacddd\utilities\status;

use tacddd\utilities\containers\ContainerService;
use tacddd\utilities\status\value_objects\Outcome;

/**
 * 状態ファクトリ
 */
final class StatusFactoryService
{
    /**
     * `結果`を返します。
     *
     * @param  self|string|int|float|bool|Outcome $value 値
     * @return Outcome                            結果
     */
    public static function createOutcome(string|int|float|bool|Outcome $value): Outcome
    {
        return ContainerService::getValueObjectCacheService()->cacheableFromString(Outcome::class, Outcome::normalize($value) ? 'true' : 'false');
    }

    /**
     * `結果`が`成功`である状態を返します。
     *
     * @return self `結果`が`成功`である状態
     */
    public static function createSuccess(): Outcome
    {
        return ContainerService::getValueObjectCacheService()->cacheableFromString(Outcome::class, 'true');
    }

    /**
     * `結果`が`失敗`である状態を返します。
     *
     * @return self `結果`が`失敗`である状態
     */
    public static function createFailure(): Outcome
    {
        return ContainerService::getValueObjectCacheService()->cacheableFromString(Outcome::class, 'false');
    }
}
