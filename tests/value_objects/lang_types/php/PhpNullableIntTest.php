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

namespace tacddd\tests\value_objects\lang_types\php;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use tacddd\tests\utilities\data_set\TypeDataSet;
use tacddd\tests\utilities\specs\value_objects\AbstractValueObjectClassSpec;
use tacddd\tests\utilities\test_cases\value_objects\AbstractValueObjectTestCase;
use tacddd\tests\utilities\test_cases\value_objects\traits\ValueObjectConstructTypeErrorFromEnumTestCaseTrait;
use tacddd\value_objects\interfaces\ValueObjectInterface;
use tacddd\value_objects\lang_types\php\abstracts\AbstractPhpNullableInt;
use tacddd\value_objects\lang_types\php\PhpNullableInt;
use tacddd\value_objects\lang_types\php\traits\factory_methods\PhpNullableIntFactoryMethodTrait;

/**
 * 言語型：PHP：nullable int
 * @internal
 */
#[CoversClass(AbstractPhpNullableInt::class)]
#[CoversClass(PhpNullableInt::class)]
class PhpNullableIntTest extends AbstractValueObjectTestCase
{
    use ValueObjectConstructTypeErrorFromEnumTestCaseTrait;

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
    public static function adjustDataProvider(): iterable
    {
        foreach (TypeDataSet::typeDataSetWithInclusion([
            'null',
            'int',
            'float',
        ]) as $type_data_set) {
            foreach ($type_data_set as $data_set) {
                yield [
                    $data_set['value'],
                    $data_set['value'] === null ? $data_set['value'] : (int) $data_set['value'],
                ];
            }
        }
    }

    /**
     * アジャスタエラー用データプロバイダ
     *
     * データセット様式：
     *  [
     *      mixed   $value, // 値オブジェクトコンストラクタに与える値
     *      string  $type,  // 値オブジェクトコンストラクタに与える値の型
     *  ]
     *
     * @return iterable アジャスタ用データ
     */
    public static function adjustErrorDataProvider(): iterable
    {
        foreach (TypeDataSet::typeDataSetWithExclusion([
            'null',
            'float',
            'empty_string',
            'multibyte_string',
            'string',
            'class',
            'interface',
            'trait',
            'int',
        ]) as $type_data_set) {
            foreach ($type_data_set as $data_set) {
                yield [
                    $data_set['value'],
                    $data_set['type'],
                ];
            }
        }
    }

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
    public static function constructDataProvider(): iterable
    {
        foreach (TypeDataSet::typeDataSetWithInclusion([
            'null',
            'int',
        ]) as $type_data_set) {
            foreach ($type_data_set as $data_set) {
                yield [
                    $data_set['value'],
                    $data_set['value'] === null ? $data_set['value'] : (int) $data_set['value'],
                ];
            }
        }
    }

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
    public static function constructTypeErrorDataProvider(): iterable
    {
        foreach (TypeDataSet::typeDataSetWithExclusion([
            'null',
            'float',
            'empty_string',
            'multibyte_string',
            'string',
            'class',
            'interface',
            'int',
        ]) as $type_data_set) {
            foreach ($type_data_set as $data_set) {
                yield [
                    $data_set['value'],
                    $data_set['type'],
                ];
            }
        }
    }

    /**
     * テスト対象クラススペックを返します。
     *
     * @return AbstractValueObjectClassSpec テスト対象クラススペック
     */
    public static function getClassSpec(): AbstractValueObjectClassSpec
    {
        return new class() extends AbstractValueObjectClassSpec {
            /**
             * 期待される値オブジェクトのユビキタス言語名を返します。
             *
             * @return string 期待される値オブジェクトのユビキタス言語名
             */
            public function getExpectedUbiquitousLanguageName(): string
            {
                return 'PHP nullable int 型';
            }

            /**
             * 期待されるプリミティブ型を返します。
             *
             * @return string 期待されるプリミティブ型
             */
            public function getPrimitiveType(): string
            {
                return '?int';
            }

            /**
             * 期待されるアジャスタ受け入れ可能型を返します。
             *
             * @return string 期待されるアジャスタ受け入れ可能型
             */
            public function getAdjustParamType(): string
            {
                return 'string|int|float|null';
            }

            /**
             * テスト対象のクラスパスを返します。
             *
             * @return string テスト対象の値クラスパス
             */
            public function getClassPath(): string
            {
                return PhpNullableInt::class;
            }

            /**
             * 期待されるクラス継承構造を返します。
             *
             * @return array 期待されるクラス継承構造
             */
            public function getExpectedExceptExtendClasses(): array
            {
                return [
                    AbstractPhpNullableInt::class,
                ];
            }

            /**
             * 期待される実装済みインターフェースを返します。
             *
             * @return array 期待される実装済みインターフェース
             */
            public function getExpectedImplementInterfaces(): array
            {
                return [
                    ValueObjectInterface::class,
                ];
            }

            /**
             * 期待される使用済み特性を返します。
             *
             * @return array 期待される使用済み特性
             */
            public function getExpectedUsingTraits(): array
            {
                return [
                    PhpNullableIntFactoryMethodTrait::class,
                ];
            }
        };
    }

    public static function formatValidatoErrorDataProvider(): iterable
    {
        foreach (TypeDataSet::typeDataSetWithInclusion([
            'empty_string',
            'multibyte_string',
            'string',
            'class',
            'interface',
        ]) as $type_data_set) {
            foreach ($type_data_set as $data_set) {
                yield [
                    $data_set['value'],
                ];
            }
        }

        yield [\sprintf(
            '%s%02d',
            \substr((string) \PHP_INT_MAX, 0, -2),
            ((int) \substr((string) \PHP_INT_MAX, -2)) + 1,
        )];

        yield [\sprintf(
            '%s%02d',
            \substr((string) -\PHP_INT_MIN, 0, -2),
            ((int) \substr((string) \PHP_INT_MIN, -2)) - 1,
        )];
    }

    public static function limitValueDataProvider(): iterable
    {
        yield [\PHP_INT_MIN];

        yield [\PHP_INT_MAX];
    }

    #[Test]
    #[DataProvider('formatValidatoErrorDataProvider')]
    #[TestDox('formatValidatoError [#$_dataName] value: $value')]
    public function formatValidatoError(mixed $value): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(\sprintf(
            '整数に利用できない文字列が指定されました。value:"%s"',
            (string) $value,
        ));

        $class_path = $this->getClassSpec()->getClassPath();

        $class_path::adjust($value);
    }

    #[Test]
    #[DataProvider('limitValueDataProvider')]
    #[TestDox('limitValue [#$_dataName] value: $value, type: $type')]
    public function limitValue(mixed $value): void
    {
        $class_path = $this->getClassSpec()->getClassPath();

        $this->assertSame($value, $class_path::adjust($value));
    }
}
