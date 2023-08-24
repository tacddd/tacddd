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

namespace tacddd\services\utilities\date_times;

/**
 * 日付ユーティリティサービス
 */
final class DateTimeUtilityService
{
    /**
     * @var array 日付計算結果キャッシュ
     */
    private static array $cache = [];

    /**
     * 翌営業日を計算して返します。
     *
     * @param  \DateTimeImmutable $dateTime           日付
     * @param  array              $holiday_map        祝日マップ ['YYYY/mm/dd' => 'YYYY/mm/dd'] 形式であること
     * @param  bool               $substitute_holiday 振替休日を加味するかどうか
     * @param  bool               $saturday_is_closed 土曜日を休みとするかどうか
     * @return \DateTimeImmutable 翌営業日
     */
    public static function createNextWorkingDate(
        \DateTimeImmutable $dateTime,
        array $holiday_map,
        bool $substitute_holiday = true,
        bool $saturday_is_closed = true,
    ): \DateTimeImmutable {
        $cache_key  = $dateTime->format('Y/m/d');

        $base_cache_key = \md5(\serialize([
            $holiday_map,
            $substitute_holiday,
            $saturday_is_closed,
        ]));

        forward:

        if (isset(self::$cache[__FUNCTION__][$base_cache_key][$cache_key])) {
            return self::$cache[__FUNCTION__][$base_cache_key][$cache_key];
        }

        $day_diff = match ($w = $dateTime->format('w')) {
            '0'     => 1,
            '6'     => $saturday_is_closed ? 2 : null,
            default => null,
        };

        if ($substitute_holiday && $w === '1') {
            if (isset($holiday_map[$dateTime->modify('-1 day')->format('Y/m/d')])) {
                $day_diff   = 1;
            }
        }

        if ($day_diff !== null) {
            $dateTime   = self::createNextWorkingDate(
                $dateTime->modify(\sprintf('%+d day', $day_diff)),
                $holiday_map,
                $substitute_holiday,
                $saturday_is_closed,
            );
        }

        if (isset($holiday_map[$dateTime->format('Y/m/d')])) {
            $dateTime   = $dateTime->modify('+1 day');

            goto forward;
        }

        return $cache[__FUNCTION__][$base_cache_key][$cache_key]  = $dateTime;
    }
}
