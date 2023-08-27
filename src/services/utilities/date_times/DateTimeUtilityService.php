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
class DateTimeUtilityService
{
    /**
     * @var array 日付計算結果キャッシュ
     */
    private array $cache = [];

    /**
     * Constructors
     *
     * @param array $holidayMap       祝日マップ ['YYYY/mm/dd' => '任意の値'] 形式であること
     * @param bool  $saturdayIsClosed 土曜日を休みとするかどうか
     * @param bool  $monthCrossing    月を跨ぐかどうか
     */
    public function __construct(
        private readonly array $holidayMap = [],
        private readonly bool $saturdayIsClosed = true,
        private readonly bool $monthCrossing = true,
    ) {
    }

    /**
     * 翌営業日を計算して返します。
     *
     * @param  \DateTimeImmutable $baseDateTime 日付
     * @return \DateTimeImmutable 翌営業日
     */
    public function createNextWorkingDate(
        \DateTimeImmutable $baseDateTime,
    ): \DateTimeImmutable {
        $dateTime   = $baseDateTime;

        $cache_key  = $dateTime->format('Y/m/d');

        if (!$this->monthCrossing) {
            $base_ym    = $dateTime->format('Y/m');
        }

        forward:

        if (isset($this->cache[__FUNCTION__][$cache_key])) {
            return $this->cache[__FUNCTION__][$cache_key];
        }

        $day_diff = match ($w = $dateTime->format('w')) {
            '0'     => 1,
            '6'     => $this->saturdayIsClosed ? 2 : null,
            default => null,
        };

        if ($day_diff !== null) {
            $dateTime = $dateTime->modify(\sprintf('%+d day', $day_diff));
        }

        if (isset($this->holidayMap[$dateTime->format('Y/m/d')])) {
            $dateTime   = $dateTime->modify('+1 day');

            goto forward;
        }

        if ($this->monthCrossing) {
            return $cache[__FUNCTION__][$cache_key]  = $dateTime;
        }

        if ($base_ym !== $dateTime->format('Y/m')) {
            $dateTime   = $baseDateTime;

            backward:

            $backword_diff  = match ($w = $dateTime->format('w')) {
                '0'     => $this->saturdayIsClosed ? -2 : -1,
                '1'     => 1 - $w - $this->saturdayIsClosed ? -3 : -2,
                '6'     => 1 - $w - $this->saturdayIsClosed ? -1 : 0,
                default => 1 - $w,
            };

            $dateTime = $backword_diff === null ? $baseDateTime : $baseDateTime->modify(
                \sprintf('%+d day', $backword_diff),
            );

            if (isset($this->holidayMap[$dateTime->format('Y/m/d')])) {
                $dateTime   = $dateTime->modify('-1 day');

                goto backward;
            }
        }

        return $cache[__FUNCTION__][$cache_key]  = $dateTime;
    }
}
