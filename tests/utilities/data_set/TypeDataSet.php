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

namespace tacddd\tests\utilities\data_set;

use tacddd\tests\utilities\resources\dummy\enums\BackedSuit;
use tacddd\tests\utilities\resources\dummy\objects\Dummy;
use tacddd\tests\utilities\resources\dummy\objects\DummyInterface;
use tacddd\tests\utilities\resources\dummy\objects\DummyTrait;

/**
 * 最低限のテストに利用する型情報セット
 */
final class TypeDataSet
{
    /**
     * 型情報セットを纏めて返します。
     *
     * @return array 型情報セット
     */
    public static function typeDataSet(): array
    {
        return [
            'null'              => self::typeNull(),
            'empty_string'      => self::typeEmptyString(),
            'multibyte_string'  => self::typeMultibyteString(),
            'string'            => self::typeString(),
            'class'             => self::typeClass(),
            'interface'         => self::typeInterface(),
            'trait'             => self::typeTrait(),
            'std_class_object'  => self::typeStdClassObject(),
            'object'            => self::typeObject(),
            'enum'              => self::typeEnum(),
            'int'               => self::typeInt(),
            'float'             => self::typeFloat(),
            'bool'              => self::typeBool(),
            'empty_array'       => self::typeEmptyArray(),
            'array'             => self::typeArray(),
            'list'              => self::typeList(),
            'resource'          => self::typeResource(),
        ];
    }

    /**
     * 指定した型を除外した型情報セットを纏めて返します。
     *
     * @param  array|string $exclusion_types 除外する型
     * @return array        型情報セット
     */
    public static function typeDataSetWithExclusion(array|string $exclusion_types): array
    {
        if (!\is_array($exclusion_types)) {
            $exclusion_types    = [$exclusion_types];
        }

        $type_data_set = self::typeDataSet();

        foreach ($exclusion_types as $exclusion_type) {
            unset($type_data_set[$exclusion_type]);
        }

        return $type_data_set;
    }

    /**
     * 指定した型のみの型情報セットを纏めて返します。
     *
     * @param  array|string $inclusion_types 取得する型
     * @return array        型情報セット
     */
    public static function typeDataSetWithInclusion(array|string $inclusion_types): array
    {
        if (!\is_array($inclusion_types)) {
            $inclusion_types    = [$inclusion_types];
        }

        $type_data_set      = [];
        $base_type_data_set = self::typeDataSet();

        foreach ($inclusion_types as $inclusion_type) {
            !isset($base_type_data_set[$inclusion_type]) ?: $type_data_set[$inclusion_type] = $base_type_data_set[$inclusion_type];
        }

        return $type_data_set;
    }

    /**
     * @return array string型情報セット（空文字）
     */
    public static function typeEmptyString(): array
    {
        return [
            ['type' => 'string', 'value' => ''],
        ];
    }

    /**
     * @return array string型情報セット（マルチバイト）
     */
    public static function typeMultibyteString(): array
    {
        return [
            ['type' => 'string', 'value' => '♤'],
            ['type' => 'string', 'value' => 'あかさたなはまやらわ'],
            ['type' => 'string', 'value' => \sprintf('あかさたな%sはまやらわ', "\n")],
        ];
    }

    /**
     * @return array string型情報セット
     */
    public static function typeString(): array
    {
        return [
            ['type' => 'string', 'value' => 'asdfzxcv'],
            ['type' => 'string', 'value' => \sprintf('asdf%szxcv', "\n")],
        ];
    }

    /**
     * @return array string型情報セット（object class）
     */
    public static function typeClass(): array
    {
        return [
            ['type' => 'string', 'value' => Dummy::class],
        ];
    }

    /**
     * @return array string型情報セット（object interface）
     */
    public static function typeInterface(): array
    {
        return [
            ['type' => 'string', 'value' => DummyInterface::class],
        ];
    }

    /**
     * @return array string型情報セット（trait）
     */
    public static function typeTrait(): array
    {
        return [
            ['type' => 'string', 'value' => DummyTrait::class],
        ];
    }

    /**
     * @return array object型情報セット（stdClass）
     */
    public static function typeStdClassObject(): array
    {
        return [
            ['type' => 'stdClass', 'value' => (object) []],
            ['type' => 'stdClass', 'value' => (object) ['value' => 1]],
        ];
    }

    /**
     * @return array object型情報セット
     */
    public static function typeObject(): array
    {
        return [
            ['type' => 'tacddd\tests\utilities\resources\dummy\objects\Dummy', 'value' => new Dummy()],
        ];
    }

    /**
     * @return array 列挙型情報セット
     */
    public static function typeEnum(): array
    {
        return [
            ['type' => 'tacddd\tests\utilities\resources\dummy\enums\BackedSuit', 'value' => BackedSuit::Hearts],
        ];
    }

    /**
     * @return array int型情報セット
     */
    public static function typeInt(): array
    {
        return [
            ['type' => 'int', 'value' => 10],
            ['type' => 'int', 'value' => 1],
            ['type' => 'int', 'value' => 0],
            ['type' => 'int', 'value' => -1],
            ['type' => 'int', 'value' => -10],
        ];
    }

    /**
     * @return array float型情報セット
     */
    public static function typeFloat(): array
    {
        return [
            ['type' => 'float', 'value' => 10.0],
            ['type' => 'float', 'value' => 1.0],
            ['type' => 'float', 'value' => 0.0],
            ['type' => 'float', 'value' => -1.0],
            ['type' => 'float', 'value' => -10.0],
            ['type' => 'float', 'value' => 1.234],
            ['type' => 'float', 'value' => 1.2e3],
            ['type' => 'float', 'value' => 7E-10],
            ['type' => 'float', 'value' => 1_234.567],
            ['type' => 'float', 'value' => 1_234.567],
            ['type' => 'float', 'value' => 1_234.567],
        ];
    }

    /**
     * @return array bool型情報セット
     */
    public static function typeBool(): array
    {
        return [
            ['type' => 'bool', 'value' => true],
            ['type' => 'bool', 'value' => false],
        ];
    }

    /**
     * @return array null型情報セット
     */
    public static function typeNull(): array
    {
        return [
            ['type' => 'null', 'value' => null],
        ];
    }

    /**
     * @return array 配列型情報セット（空配列）
     */
    public static function typeEmptyArray(): array
    {
        return [
            ['type' => 'array', 'value' => []],
        ];
    }

    /**
     * @return array 配列型情報セット
     */
    public static function typeArray(): array
    {
        return [
            ['type' => 'array', 'value' => [1 => 1]],
            ['type' => 'array', 'value' => [1 => 1, 2 => 2]],
            ['type' => 'array', 'value' => ['a' => 1]],
        ];
    }

    /**
     * @return array 配列型情報セット（リスト）
     */
    public static function typeList(): array
    {
        return [
            ['type' => 'array', 'value' => [1]],
            ['type' => 'array', 'value' => ['a', 'b']],
        ];
    }

    /**
     * @return array リソース型情報セット
     */
    public static function typeResource(): array
    {
        return [
            ['type' => 'resource', 'value' => \fopen('php://input', 'rb')],
        ];
    }

    private function __construct()
    {
    }
}
