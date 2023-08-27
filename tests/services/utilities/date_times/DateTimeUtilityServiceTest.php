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

namespace tacddd\tests\services\utilities\date_times;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use tacddd\services\utilities\date_times\DateTimeUtilityService;
use tacddd\tests\utilities\test_cases\AbstractTestCase;

/**
 * @internal
 */

/**
 * @internal
 */
class DateTimeUtilityServiceTest extends AbstractTestCase
{
    private const HOLIDAY_MAP   = [
        '2023/01/01' => '2023/01/01',
        '2023/04/29' => '2023/04/29',
        '2023/05/03' => '2023/05/03',
        '2023/05/04' => '2023/05/04',
        '2023/05/05' => '2023/05/05',
        '2023/07/17' => '2023/07/17',
        '2023/08/11' => '2023/08/11',
        '2023/09/23' => '2023/09/23',
        '2023/10/09' => '2023/10/09',
    ];

    public static function createNextWorkingDateEnabledSubstituteHolidayAndSaturdayClosedDataProvider(): iterable
    {
        return [
            ['2022/12/30', '2022/12/30'],
            ['2023/01/03', '2022/12/31'],
            ['2023/01/03', '2023/01/01'],
            ['2023/01/03', '2023/01/02'],
            ['2023/01/03', '2023/01/03'],

            ['2023/04/28', '2023/04/28'],
            ['2023/05/01', '2023/04/29'],
            ['2023/05/01', '2023/04/30'],
            ['2023/05/01', '2023/05/01'],

            ['2023/05/02', '2023/05/02'],
            ['2023/05/08', '2023/05/03'],
            ['2023/05/08', '2023/05/04'],
            ['2023/05/08', '2023/05/05'],
            ['2023/05/08', '2023/05/06'],
            ['2023/05/08', '2023/05/07'],
            ['2023/05/08', '2023/05/08'],
            ['2023/05/09', '2023/05/09'],

            ['2023/07/14', '2023/07/14'],
            ['2023/07/18', '2023/07/15'],
            ['2023/07/18', '2023/07/16'],
            ['2023/07/18', '2023/07/17'],
            ['2023/07/18', '2023/07/18'],
            ['2023/07/19', '2023/07/19'],

            ['2023/08/10', '2023/08/10'],
            ['2023/08/14', '2023/08/11'],
            ['2023/08/14', '2023/08/12'],
            ['2023/08/14', '2023/08/13'],
            ['2023/08/14', '2023/08/14'],

            ['2023/09/22', '2023/09/22'],
            ['2023/09/25', '2023/09/23'],
            ['2023/09/25', '2023/09/24'],
            ['2023/09/25', '2023/09/25'],

            ['2023/10/06', '2023/10/06'],
            ['2023/10/10', '2023/10/07'],
            ['2023/10/10', '2023/10/08'],
            ['2023/10/10', '2023/10/09'],
            ['2023/10/10', '2023/10/10'],
            ['2023/10/11', '2023/10/11'],
        ];
    }

    #[Test]
    public function createNextWorkingDate(): void
    {
        $this->assertSame('2023/08/14', DateTimeUtilityService::createNextWorkingDate(
            new \DateTimeImmutable('2023/08/11'),
            static::HOLIDAY_MAP,
            true,
            true,
        )->format('Y/m/d'));

        $this->assertSame('2023/08/14', DateTimeUtilityService::createNextWorkingDate(
            new \DateTimeImmutable('2023/08/11'),
            static::HOLIDAY_MAP,
            false,
            true,
        )->format('Y/m/d'));

        $this->assertSame('2023/08/12', DateTimeUtilityService::createNextWorkingDate(
            new \DateTimeImmutable('2023/08/11'),
            static::HOLIDAY_MAP,
            true,
            false,
        )->format('Y/m/d'));
    }

    #[Test]
    #[DataProvider('createNextWorkingDateEnabledSubstituteHolidayAndSaturdayClosedDataProvider')]
    #[TestDox('createNextWorkingDateEnabledSubstituteHolidayAndSaturdayClosed [#$_dataName] expected: $expected, actual: $actual')]
    public function createNextWorkingDateEnabledSubstituteHolidayAndSaturdayClosed(string $expected, string $actual): void
    {
        $this->assertSame($expected, DateTimeUtilityService::createNextWorkingDate(
            new \DateTimeImmutable($actual),
            static::HOLIDAY_MAP,
            true,
            true,
        )->format('Y/m/d'));
    }
}
