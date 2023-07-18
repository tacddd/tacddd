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

namespace tacddd\tests\utilities\test_cases\value_objects;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use tacddd\tests\utilities\specs\value_objects\AbstractValueObjectClassSpec;
use tacddd\tests\utilities\test_cases\AbstractTestCase;
use tacddd\value_objects\interfaces\ValueObjectInterface;

/**
 * @internal
 */
abstract class AbstractValueObjectTestCase extends AbstractTestCase
{
    /**
     * アジャスタ用データプロバイダ
     *
     * データセット様式：
     *  [
     *      mixed   $value,     // 値オブジェクトコンストラクタに与える値
     *      mixed   $expected,  // 期待する値オブジェクトの値
     *  ]
     *
     * @return iterable アジャスタ用データ
     */
    abstract public static function adjustDataProvider(): iterable;

    /**
     * アジャスタタイプエラーテスト用データプロバイダ
     *
     * データセット様式：
     *  [
     *      mixed   $value, // 値オブジェクトコンストラクタに与える値
     *      string  $type,  // 値オブジェクトコンストラクタに与える値の型
     *  ]
     *
     * @return iterable アジャスタタイプエラーテスト用データ
     */
    abstract public static function adjustErrorDataProvider(): iterable;

    /**
     * コンストラクタテスト用データプロバイダ
     *
     * データセット様式：
     *  [
     *      mixed   $value,     // 値オブジェクトコンストラクタに与える値
     *      mixed   $expected,  // 期待する値オブジェクトの値
     *  ]
     *
     * @return iterable コンストラクタテスト用データ
     */
    abstract public static function constructDataProvider(): iterable;

    /**
     * コンストラクタタイプエラーテスト用データプロバイダ
     *
     * データセット様式：
     *  [
     *      mixed   $value, // 値オブジェクトコンストラクタに与える値
     *      string  $type,  // 値オブジェクトコンストラクタに与える値の型
     *  ]
     *
     * @return iterable コンストラクタタイプエラーテスト用データ
     */
    abstract public static function constructTypeErrorDataProvider(): iterable;

    /**
     * テスト対象クラススペックを返します。
     *
     * @return AbstractValueObjectClassSpec テスト対象クラススペック
     */
    abstract public static function getClassSpec(): AbstractValueObjectClassSpec;

    #[Test]
    public function selfOf(): void
    {
        $value = static::constructDataProvider();

        $value = match (true) {
            $value instanceof \Generator => $value->current(),
            default                      => \current($value),
        };

        $value          = \array_key_exists(0, $value) ? $value[0] : (\array_key_exists('value', $value) ? $value['value'] : null);

        $class_path = static::getClassSpec()->getClassPath();

        $this->assertSame($object = new $class_path($value), $class_path::of($object));
    }

    #[Test]
    #[DataProvider('adjustDataProvider')]
    #[TestDox('adjust [#$_dataName] value: $value, expected: $expected')]
    public function adjust(mixed $value, mixed $expected): void
    {
        $this->assertSame($expected, static::getClassSpec()->getClassPath()::adjust($value));
    }

    #[Test]
    #[DataProvider('adjustErrorDataProvider')]
    #[TestDox('adjustError [#$_dataName] value: $value, type: $type')]
    public function adjustError(mixed $value, string $type): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessageMatches(\sprintf(
            '/must be of type %s, %s given/',
            \preg_quote(static::getClassSpec()->getAdjustParamType()),
            \preg_quote($type),
        ));

        static::getClassSpec()->getClassPath()::adjust($value);
    }

    #[Test]
    #[DataProvider('constructDataProvider')]
    #[TestDox('construct [#$_dataName] value: $value, expected: $expected')]
    public function construct(mixed $value, mixed $expected): void
    {
        $class_path = static::getClassSpec()->getClassPath();

        $valueObject    = new $class_path($value);
        $this->assertInstanceOf($class_path, $valueObject);
        $this->assertSame($expected, $valueObject->value);
    }

    #[Test]
    #[DataProvider('adjustDataProvider')]
    #[TestDox('of [#$_dataName] value: $value, expected: $expected')]
    public function of(mixed $value, mixed $expected): void
    {
        $class_path     = static::getClassSpec()->getClassPath();
        $valueObject    = $class_path::of($value);

        $this->assertInstanceOf($class_path, $valueObject);
        $this->assertSame($expected, $valueObject->value);
    }

    #[Test]
    #[DataProvider('constructTypeErrorDataProvider')]
    #[TestDox('constructTypeError [#$_dataName] value: $value, type: $type')]
    public function constructTypeError(mixed $value, string $type): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessageMatches(\sprintf(
            '/must be of type %s, %s given/',
            \preg_quote(static::getClassSpec()->getPrimitiveType()),
            \preg_quote($type),
        ));

        $class_path = static::getClassSpec()->getClassPath();

        new $class_path($value);
    }

    #[Test]
    final public function valueObject(): void
    {
        $classSpec  = static::getClassSpec();

        /** @var ValueObjectInterface&string $class_path */
        $class_path = $classSpec->getClassPath();

        $this->assertTrue(\class_exists($class_path, true), \sprintf('対象となる値オブジェクトクラス（%s）が存在していないか、アクセスできません。', $class_path));

        // ユビキタス言語名
        $this->assertSame($classSpec->getExpectedUbiquitousLanguageName(), $class_path::getName());

        // 継承構造
        $expected_extend_classes    = $classSpec->getExpectedExceptExtendClasses();
        \sort($expected_extend_classes);

        $actual_extended_classes    = \class_parents($class_path);
        \sort($actual_extended_classes);

        $this->assertSame($expected_extend_classes, $actual_extended_classes);

        // インターフェース
        $expected_implement_classes = $classSpec->getExpectedImplementInterfaces();
        \sort($expected_implement_classes);

        $actual_implement_classes       = \class_implements($class_path);
        \sort($actual_implement_classes);

        $this->assertSame($expected_implement_classes, $actual_implement_classes);

        // 特性
        $expected_using_traits  = $classSpec->getExpectedUsingTraits();
        \sort($expected_using_traits);

        $actual_using_traits    = \class_uses($class_path);

        foreach ($actual_extended_classes as $actual_extended_class) {
            $actual_using_traits    = \array_merge($actual_using_traits, \class_uses($actual_extended_class));
        }

        \sort($actual_using_traits);
        $actual_using_traits    = \array_unique($actual_using_traits);

        $this->assertSame($expected_using_traits, $actual_using_traits);
    }
}
