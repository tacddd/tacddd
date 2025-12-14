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
 * @version     1.0.0
 */

declare(strict_types=1);

namespace tacddd\tests\cases\utilities\date_times;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use tacddd\tests\utilities\AbstractTestCase;
use tacddd\tests\utilities\data_set\Holiday;
use tacddd\utilities\date_times\DateTimeUtilityService;

/**
 * @internal
 */

/**
 * @internal
 */
class DateTimeUtilityServiceTest extends AbstractTestCase
{
    public static function createNextWorkingDateSaturdayIsClosedAndMonthCrossingDataProvider(): iterable
    {
        // ==============================================
        // 土曜日を休みとする
        // 月を跨ぐ
        $dateTimeUtilityService = new DateTimeUtilityService(Holiday::MAP, true, true);

        foreach ([
            // 2021/07/31 月末の土曜日
            ['2021/07/30', '2021/07/30'], // 金
            ['2021/08/02', '2021/07/31'], // 土
            ['2021/08/02', '2021/08/01'], // 日
            ['2021/08/02', '2021/08/02'], // 月

            // 2021/10/31 月末の日曜日
            ['2021/10/29', '2021/10/29'], // 金
            ['2021/11/01', '2021/10/30'], // 土
            ['2021/11/01', '2021/10/31'], // 日
            ['2021/11/01', '2021/11/01'], // 月

            // 2023/01/02 振替休日
            ['2022/12/30', '2022/12/30'], // 金
            ['2023/01/03', '2022/12/31'], // 土
            ['2023/01/03', '2023/01/01'], // 日（祝）
            ['2023/01/03', '2023/01/02'], // 月（祝）
            ['2023/01/03', '2023/01/03'], // 火

            // 2023/01/09 日曜翌日の祝日
            ['2023/01/06', '2023/01/06'], // 金
            ['2023/01/10', '2023/01/07'], // 土
            ['2023/01/10', '2023/01/08'], // 日
            ['2023/01/10', '2023/01/09'], // 月（祝）
            ['2023/01/10', '2023/01/10'], // 火

            // 2023/01/15 通常の週末
            ['2023/01/13', '2023/01/13'], // 金
            ['2023/01/16', '2023/01/14'], // 土
            ['2023/01/16', '2023/01/15'], // 日
            ['2023/01/16', '2023/01/16'], // 月

            // 2023/05/03 GW
            ['2023/05/02', '2023/05/02'], // 火
            ['2023/05/08', '2023/05/03'], // 水（祝）
            ['2023/05/08', '2023/05/04'], // 木（祝）
            ['2023/05/08', '2023/05/05'], // 金（祝）
            ['2023/05/08', '2023/05/06'], // 土
            ['2023/05/08', '2023/05/07'], // 日
            ['2023/05/08', '2023/05/08'], // 月

            // 2023/08/11 金曜が祝日
            ['2023/08/10', '2023/08/10'], // 木
            ['2023/08/14', '2023/08/11'], // 金（祝）
            ['2023/08/14', '2023/08/12'], // 土
            ['2023/08/14', '2023/08/13'], // 日
            ['2023/08/14', '2023/08/14'], // 月

            // 2023/09/23 土曜が祝日
            ['2023/09/22', '2023/09/22'], // 金
            ['2023/09/25', '2023/09/23'], // 土（祝）
            ['2023/09/25', '2023/09/24'], // 日
            ['2023/09/25', '2023/09/25'], // 月
        ] as $row) {
            yield [$dateTimeUtilityService, ...$row];
        }
    }

    public static function createNextWorkingDateSaturdayIsClosedDataProvider(): iterable
    {
        // ==============================================
        // 土曜日を休みとする
        // 月を跨がない
        $dateTimeUtilityService = new DateTimeUtilityService(Holiday::MAP, true, false);

        foreach ([
            // 2021/07/31 月末の土曜日
            ['2021/07/30', '2021/07/30'], // 金
            ['2021/07/30', '2021/07/31'], // 土
            ['2021/08/02', '2021/08/01'], // 日
            ['2021/08/02', '2021/08/02'], // 月

            // 2021/10/31 月末の日曜日
            ['2021/10/29', '2021/10/29'], // 金
            ['2021/10/29', '2021/10/30'], // 土
            ['2021/10/29', '2021/10/31'], // 日
            ['2021/11/01', '2021/11/01'], // 月

            // 2023/01/02 振替休日
            ['2022/12/30', '2022/12/30'], // 金
            ['2022/12/30', '2022/12/31'], // 土
            ['2023/01/03', '2023/01/01'], // 日（祝）
            ['2023/01/03', '2023/01/02'], // 月（祝）
            ['2023/01/03', '2023/01/03'], // 火

            // 2023/01/09 日曜翌日の祝日
            ['2023/01/06', '2023/01/06'], // 金
            ['2023/01/10', '2023/01/07'], // 土
            ['2023/01/10', '2023/01/08'], // 日
            ['2023/01/10', '2023/01/09'], // 月（祝）
            ['2023/01/10', '2023/01/10'], // 火

            // 2023/01/15 通常の週末
            ['2023/01/13', '2023/01/13'], // 金
            ['2023/01/16', '2023/01/14'], // 土
            ['2023/01/16', '2023/01/15'], // 日
            ['2023/01/16', '2023/01/16'], // 月

            // 2023/05/03 GW
            ['2023/05/02', '2023/05/02'], // 火
            ['2023/05/08', '2023/05/03'], // 水（祝）
            ['2023/05/08', '2023/05/04'], // 木（祝）
            ['2023/05/08', '2023/05/05'], // 金（祝）
            ['2023/05/08', '2023/05/06'], // 土
            ['2023/05/08', '2023/05/07'], // 日
            ['2023/05/08', '2023/05/08'], // 月

            // 2023/08/11 金曜が祝日
            ['2023/08/10', '2023/08/10'], // 木
            ['2023/08/14', '2023/08/11'], // 金（祝）
            ['2023/08/14', '2023/08/12'], // 土
            ['2023/08/14', '2023/08/13'], // 日
            ['2023/08/14', '2023/08/14'], // 月

            // 2023/09/23 土曜が祝日
            ['2023/09/22', '2023/09/22'], // 金
            ['2023/09/25', '2023/09/23'], // 土（祝）
            ['2023/09/25', '2023/09/24'], // 日
            ['2023/09/25', '2023/09/25'], // 月
        ] as $row) {
            yield [$dateTimeUtilityService, ...$row];
        }
    }

    public static function createNextWorkingDateMonthCrossingDataProvider(): iterable
    {
        // ==============================================
        // 土曜日を営業日とする
        // 月を跨ぐ
        $dateTimeUtilityService = new DateTimeUtilityService(Holiday::MAP, false, true);

        foreach ([
            // 2021/07/31 月末の土曜日
            ['2021/07/30', '2021/07/30'], // 金
            ['2021/07/31', '2021/07/31'], // 土
            ['2021/08/02', '2021/08/01'], // 日
            ['2021/08/02', '2021/08/02'], // 月

            // 2021/10/31 月末の日曜日
            ['2021/10/29', '2021/10/29'], // 金
            ['2021/10/30', '2021/10/30'], // 土
            ['2021/11/01', '2021/10/31'], // 日
            ['2021/11/01', '2021/11/01'], // 月

            // 2023/01/02 振替休日
            ['2022/12/30', '2022/12/30'], // 金
            ['2022/12/31', '2022/12/31'], // 土
            ['2023/01/03', '2023/01/01'], // 日（祝）
            ['2023/01/03', '2023/01/02'], // 月（祝）
            ['2023/01/03', '2023/01/03'], // 火

            // 2023/01/09 日曜翌日の祝日
            ['2023/01/06', '2023/01/06'], // 金
            ['2023/01/07', '2023/01/07'], // 土
            ['2023/01/10', '2023/01/08'], // 日
            ['2023/01/10', '2023/01/09'], // 月（祝）
            ['2023/01/10', '2023/01/10'], // 火

            // 2023/01/15 通常の週末
            ['2023/01/13', '2023/01/13'], // 金
            ['2023/01/14', '2023/01/14'], // 土
            ['2023/01/16', '2023/01/15'], // 日
            ['2023/01/16', '2023/01/16'], // 月

            // 2023/05/03 GW
            ['2023/05/02', '2023/05/02'], // 火
            ['2023/05/06', '2023/05/03'], // 水（祝）
            ['2023/05/06', '2023/05/04'], // 木（祝）
            ['2023/05/06', '2023/05/05'], // 金（祝）
            ['2023/05/06', '2023/05/06'], // 土
            ['2023/05/08', '2023/05/07'], // 日
            ['2023/05/08', '2023/05/08'], // 月

            // 2023/08/11 金曜が祝日
            ['2023/08/10', '2023/08/10'], // 木
            ['2023/08/12', '2023/08/11'], // 金（祝）
            ['2023/08/12', '2023/08/12'], // 土
            ['2023/08/14', '2023/08/13'], // 日
            ['2023/08/14', '2023/08/14'], // 月

            // 2023/09/23 土曜が祝日
            ['2023/09/22', '2023/09/22'], // 金
            ['2023/09/25', '2023/09/23'], // 土（祝）
            ['2023/09/25', '2023/09/24'], // 日
            ['2023/09/25', '2023/09/25'], // 月
        ] as $row) {
            yield [$dateTimeUtilityService, ...$row];
        }
    }

    public static function createNextWorkingDateDataProvider(): iterable
    {
        // ==============================================
        // 土曜日を営業日とする
        // 月を跨がない
        $dateTimeUtilityService = new DateTimeUtilityService(Holiday::MAP, false, false);

        foreach ([
            // 2021/07/31 月末の土曜日
            ['2021/07/30', '2021/07/30'], // 金
            ['2021/07/31', '2021/07/31'], // 土
            ['2021/08/02', '2021/08/01'], // 日
            ['2021/08/02', '2021/08/02'], // 月

            // 2021/10/31 月末の日曜日
            ['2021/10/29', '2021/10/29'], // 金
            ['2021/10/30', '2021/10/30'], // 土
            ['2021/10/30', '2021/10/31'], // 日
            ['2021/11/01', '2021/11/01'], // 月

            // 2023/01/02 振替休日
            ['2022/12/30', '2022/12/30'], // 金
            ['2022/12/31', '2022/12/31'], // 土
            ['2023/01/03', '2023/01/01'], // 日（祝）
            ['2023/01/03', '2023/01/02'], // 月（祝）
            ['2023/01/03', '2023/01/03'], // 火

            // 2023/01/09 日曜翌日の祝日
            ['2023/01/06', '2023/01/06'], // 金
            ['2023/01/07', '2023/01/07'], // 土
            ['2023/01/10', '2023/01/08'], // 日
            ['2023/01/10', '2023/01/09'], // 月（祝）
            ['2023/01/10', '2023/01/10'], // 火

            // 2023/01/15 通常の週末
            ['2023/01/13', '2023/01/13'], // 金
            ['2023/01/14', '2023/01/14'], // 土
            ['2023/01/16', '2023/01/15'], // 日
            ['2023/01/16', '2023/01/16'], // 月

            // 2023/05/03 GW
            ['2023/05/02', '2023/05/02'], // 火
            ['2023/05/06', '2023/05/03'], // 水（祝）
            ['2023/05/06', '2023/05/04'], // 木（祝）
            ['2023/05/06', '2023/05/05'], // 金（祝）
            ['2023/05/06', '2023/05/06'], // 土
            ['2023/05/08', '2023/05/07'], // 日
            ['2023/05/08', '2023/05/08'], // 月

            // 2023/08/11 金曜が祝日
            ['2023/08/10', '2023/08/10'], // 木
            ['2023/08/12', '2023/08/11'], // 金（祝）
            ['2023/08/12', '2023/08/12'], // 土
            ['2023/08/14', '2023/08/13'], // 日
            ['2023/08/14', '2023/08/14'], // 月

            // 2023/09/23 土曜が祝日
            ['2023/09/22', '2023/09/22'], // 金
            ['2023/09/25', '2023/09/23'], // 土（祝）
            ['2023/09/25', '2023/09/24'], // 日
            ['2023/09/25', '2023/09/25'], // 月
        ] as $row) {
            yield [$dateTimeUtilityService, ...$row];
        }
    }

    #[Test]
    public function simpleCreateNextWorkingDate(): void
    {
        $dateTimeUtilityService = new DateTimeUtilityService(
            Holiday::MAP,
            true,
            true,
        );

        $this->assertSame('2023/08/14', $dateTimeUtilityService->createNextWorkingDate(
            new \DateTimeImmutable('2023/08/11'),
        )->format('Y/m/d'));

        // ==============================================
        $dateTimeUtilityService = new DateTimeUtilityService(
            Holiday::MAP,
            false,
            true,
        );

        $this->assertSame('2023/08/12', $dateTimeUtilityService->createNextWorkingDate(
            new \DateTimeImmutable('2023/08/11'),
        )->format('Y/m/d'));

        // ==============================================
        $dateTimeUtilityService = new DateTimeUtilityService(
            Holiday::MAP,
            true,
            false,
        );

        $this->assertSame('2023/08/14', $dateTimeUtilityService->createNextWorkingDate(
            new \DateTimeImmutable('2023/08/11'),
        )->format('Y/m/d'));

        // ==============================================
        $dateTimeUtilityService = new DateTimeUtilityService(
            Holiday::MAP,
            true,
            false,
        );

        $this->assertSame('2021/10/29', $dateTimeUtilityService->createNextWorkingDate(
            new \DateTimeImmutable('2021/10/31'),
        )->format('Y/m/d'));
    }

    #[Test]
    #[DataProvider('createNextWorkingDateSaturdayIsClosedAndMonthCrossingDataProvider')]
    #[TestDox('createNextWorkingDate 土曜日を休みとする, 月を跨ぐ [#$_dataName] expected: $expected, actual: $actual')]
    public function createNextWorkingDateSaturdayIsClosedAndMonthCrossing(
        DateTimeUtilityService $dateTimeUtilityService,
        string $expected,
        string $actual,
    ): void {
        $this->assertSame($expected, $dateTimeUtilityService->createNextWorkingDate(
            new \DateTimeImmutable($actual),
        )->format('Y/m/d'));
    }

    #[Test]
    #[DataProvider('createNextWorkingDateSaturdayIsClosedDataProvider')]
    #[TestDox('createNextWorkingDate 土曜日を休みとする, 月を跨がない [#$_dataName] expected: $expected, actual: $actual')]
    public function createNextWorkingDateSaturdayIsClosed(
        DateTimeUtilityService $dateTimeUtilityService,
        string $expected,
        string $actual,
    ): void {
        $this->assertSame($expected, $dateTimeUtilityService->createNextWorkingDate(
            new \DateTimeImmutable($actual),
        )->format('Y/m/d'));
    }

    #[Test]
    #[DataProvider('createNextWorkingDateMonthCrossingDataProvider')]
    #[TestDox('createNextWorkingDate 土曜日を営業日とする, 月を跨ぐ [#$_dataName] expected: $expected, actual: $actual')]
    public function createNextWorkingDateMonthCrossing(
        DateTimeUtilityService $dateTimeUtilityService,
        string $expected,
        string $actual,
    ): void {
        $this->assertSame($expected, $dateTimeUtilityService->createNextWorkingDate(
            new \DateTimeImmutable($actual),
        )->format('Y/m/d'));
    }

    #[Test]
    #[DataProvider('createNextWorkingDateDataProvider')]
    #[TestDox('createNextWorkingDate 土曜日を営業日とする, 月を跨がない [#$_dataName] expected: $expected, actual: $actual')]
    public function createNextWorkingDate(
        DateTimeUtilityService $dateTimeUtilityService,
        string $expected,
        string $actual,
    ): void {
        $this->assertSame($expected, $dateTimeUtilityService->createNextWorkingDate(
            new \DateTimeImmutable($actual),
        )->format('Y/m/d'));
    }
}
