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
use tacddd\value_objects\interfaces\ValueObjectInterface;
use tacddd\value_objects\lang_types\php\abstracts\AbstractPhpObject;
use tacddd\value_objects\lang_types\php\PhpObject;
use tacddd\value_objects\lang_types\php\traits\factory_methods\PhpObjectFactoryMethodTrait;

/**
 * 言語型：PHP：object
 * @internal
 */
#[CoversClass(AbstractPhpObject::class)]
#[CoversClass(PhpObject::class)]
class PhpObjectTest extends AbstractValueObjectTestCase
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
    public static function adjustDataProvider(): iterable
    {
        foreach (TypeDataSet::typeDataSetWithInclusion([
            'std_class_object',
            'object',
        ]) as $type_data_set) {
            foreach ($type_data_set as $data_set) {
                yield [
                    $data_set['value'],
                    $data_set['value'],
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
            'std_class_object',
            'object',
            'enum',
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
            'std_class_object',
            'object',
        ]) as $type_data_set) {
            foreach ($type_data_set as $data_set) {
                yield [
                    $data_set['value'],
                    $data_set['value'],
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
            'std_class_object',
            'object',
            'enum',
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
                return 'PHP object 型';
            }

            /**
             * 期待されるプリミティブ型を返します。
             *
             * @return string 期待されるプリミティブ型
             */
            public function getPrimitiveType(): string
            {
                return 'object';
            }

            /**
             * 期待されるアジャスタ受け入れ可能型を返します。
             *
             * @return string 期待されるアジャスタ受け入れ可能型
             */
            public function getAdjustParamType(): string
            {
                return 'object';
            }

            /**
             * テスト対象のクラスパスを返します。
             *
             * @return string テスト対象の値クラスパス
             */
            public function getClassPath(): string
            {
                return PhpObject::class;
            }

            /**
             * 期待されるクラス継承構造を返します。
             *
             * @return array 期待されるクラス継承構造
             */
            public function getExpectedExceptExtendClasses(): array
            {
                return [
                    AbstractPhpObject::class,
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
                    PhpObjectFactoryMethodTrait::class,
                ];
            }
        };
    }

    public static function notConvertDataProvider(): iterable
    {
        foreach (TypeDataSet::typeDataSetWithInclusion([
            'enum',
        ]) as $type_data_set) {
            foreach ($type_data_set as $data_set) {
                yield [
                    $data_set['value'],
                    $data_set['type'],
                ];
            }
        }
    }

    #[Test]
    #[DataProvider('notConvertDataProvider')]
    #[TestDox('notConvert [#$_dataName] value: $value, type: $type')]
    public function notConvert(mixed $value, string $type): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(\sprintf(
            '列挙型を与えられました。value:"%s"',
            $type,
        ));

        $class_path = $this->getClassSpec()->getClassPath();

        $class_path::of($value);
    }
}
