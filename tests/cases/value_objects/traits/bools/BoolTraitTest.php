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

namespace tacddd\tests\cases\value_objects\lang_types\php;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use tacddd\tests\utilities\AbstractTestCase;
use tacddd\tests\utilities\resources\dummy\value_objects\bools\traits\BoolTraitDummay;

/**
 * bool特性
 * @internal
 */
#[CoversClass(\BoolFactoryMethodTrait::class)]
#[CoversClass(\BoolFromStringTrait::class)]
#[CoversClass(\BoolNormalizationTrait::class)]
#[CoversClass(\BoolVerificationTrait::class)]
#[CoversClass(\BoolVerificationFromStringTrait::class)]
class BoolTraitTest extends AbstractTestCase
{
    public static function fromStringDataProvider(): iterable
    {
        $boolTrue   = new BoolTraitDummay(true);
        $boolFalse  = new BoolTraitDummay(false);

        yield [$boolTrue, 'true', \tacddd\to_debug_string('true')];

        yield [$boolTrue, '1', \tacddd\to_debug_string('1')];

        yield [$boolTrue, 'ON', \tacddd\to_debug_string('ON')];

        yield [$boolFalse, 'false', \tacddd\to_debug_string('false')];

        yield [$boolFalse, '0', \tacddd\to_debug_string('0')];

        yield [$boolFalse, 'OFF', \tacddd\to_debug_string('OFF')];
    }

    public static function factoryMethodDataProvider(): iterable
    {
        $boolTrue   = new BoolTraitDummay(true);
        $boolFalse  = new BoolTraitDummay(false);

        foreach (self::fromStringDataProvider() as $data) {
            yield $data;
        }

        yield [$boolTrue, $boolTrue, '同一インスタンス：true'];

        yield [$boolTrue, 1, \tacddd\to_debug_string(1)];

        yield [$boolTrue, 1.0, \tacddd\to_debug_string(1.0)];

        yield [$boolTrue, true, \tacddd\to_debug_string(true)];

        yield [$boolFalse, $boolFalse, \tacddd\to_debug_string('同一インスタンス：false')];

        yield [$boolFalse, 0, \tacddd\to_debug_string(0)];

        yield [$boolFalse, 0.0, \tacddd\to_debug_string(0.0)];

        yield [$boolFalse, false, \tacddd\to_debug_string(false)];
    }

    #[Test]
    #[DataProvider('fromStringDataProvider')]
    #[TestDox('fromString [#$_dataName] message: $message')]
    public function fromString(BoolTraitDummay $expected, string $value, string $message = ''): void
    {
        $this->assertEquals($expected, BoolTraitDummay::fromString($value), $message);
    }

    #[Test]
    #[DataProvider('factoryMethodDataProvider')]
    #[TestDox('factoryMethod [#$_dataName] message: $message')]
    public function factoryMethod(BoolTraitDummay $expected, BoolTraitDummay|string|int|float|bool $value, string $message = ''): void
    {
        $this->assertEquals($expected, BoolTraitDummay::of($value), $message);
    }

    #[Test]
    #[DataProvider('factoryMethodDataProvider')]
    #[TestDox('normalize [#$_dataName] message: $message')]
    public function normalize(BoolTraitDummay $expected, BoolTraitDummay|string|int|float|bool $value, string $message = ''): void
    {
        $this->assertSame($expected->value, BoolTraitDummay::normalize($value), $message);
    }
}
