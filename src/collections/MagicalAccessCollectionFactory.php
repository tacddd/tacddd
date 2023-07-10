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

namespace tacd\collections;

use tacd\collections\interfaces\objects\UniqueKeyFactoryInterface;
use tacd\collections\traits\objects\ObjectCollectionInterface;
use tacd\collections\traits\objects\ObjectCollectionTrait;

/**
 * マジカルアクセスコレクションファクトリ
 */
final class MagicalAccessCollectionFactory
{
    /**
     * マジカルアクセスオブジェクトコレクションを生成して返します。
     *
     * @param  string|object|array                $class           受け入れ可能なクラス
     * @param  UniqueKeyFactoryInterface|\Closure $createUniqueKey ユニークキー生成機
     * @param  array                              $elements        初期状態で投入したいオブジェクト群
     * @param  array                              $options         オプション
     * @return ObjectCollectionInterface          マジカルアクセスオブジェクトコレクション
     */
    public static function createObjectCollection(
        string|object|array $class,
        UniqueKeyFactoryInterface|\Closure $createUniqueKey,
        iterable $elements = [],
        array $options = [],
    ): ObjectCollectionInterface {
        $options['allowed_classes']     = \is_object($class) ? $class::class : $class;
        $options['create_unique_key']   = $createUniqueKey;

        return new class($elements, $options) implements ObjectCollectionInterface {
            use ObjectCollectionTrait;

            /**
             * @var string|array 受け入れ可能なクラス
             */
            private static string|array $allowedClasses;

            /**
             * @var UniqueKeyFactoryInterface|\Closure ユニークキー生成機
             */
            private static UniqueKeyFactoryInterface|\Closure $createUniqueKey;

            /**
             * constructor
             *
             * @param iterable $elements 初期状態として受け入れるオブジェクトの配列
             * @param array    $options  オプション
             */
            public function __construct(iterable $elements = [], array $options = [])
            {
                self::$allowedClasses     = $options['allowed_classes'];

                $createUniqueKey          = $options['create_unique_key'];
                self::$createUniqueKey    = $createUniqueKey instanceof UniqueKeyFactoryInterface ? $createUniqueKey::createUniqueKey(...) : $createUniqueKey;

                $this->options  = $options;

                foreach ($elements as $element) {
                    $this->add($element);
                }
            }

            /**
             * 受け入れ可能なクラスを返します。
             *
             * @return string|array 受け入れ可能なクラス
             */
            public static function getAllowedClasses(): string|array
            {
                return self::$allowedClasses;
            }

            /**
             * 指定されたオブジェクトからユニークキーを返します。
             *
             * @param  object     $element オブジェクト
             * @return int|string ユニークキー
             */
            public static function createUniqueKey(object $element): string|int
            {
                $createUniqueKey    = self::$createUniqueKey;

                return $createUniqueKey($element);
            }
        };
    }
}