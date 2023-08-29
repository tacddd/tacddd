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

namespace tacddd\collections\objects;

use tacddd\collections\interfaces\AdjustKeyFactoryInterface;
use tacddd\collections\interfaces\UniqueIdFactoryInterface;
use tacddd\collections\objects\traits\magical_accesser\ObjectCollectionMagicalAccessorInterface;
use tacddd\collections\objects\traits\magical_accesser\ObjectCollectionMagicalAccessorTrait;
use tacddd\collections\objects\traits\ObjectCollectionInterface;
use tacddd\collections\objects\traits\ObjectCollectionTrait;

/**
 * オブジェクトコレクションファクトリ
 */
final class ObjectCollectionFactory
{
    /**
     * オブジェクトコレクションを生成して返します。
     *
     * @param  string|object|array                     $class          受け入れ可能なクラス
     * @param  UniqueIdFactoryInterface|\Closure       $createUniqueId ユニークID生成機
     * @param  iterable                                $objects        初期状態で投入したいオブジェクト群
     * @param  null|AdjustKeyFactoryInterface|\Closure $adjustKey      キーアジャスタ
     * @param  array                                   $options        オプション
     * @return ObjectCollectionInterface               オブジェクトコレクション
     */
    public static function create(
        string|object|array $class,
        UniqueIdFactoryInterface|\Closure $createUniqueId,
        iterable $objects = [],
        null|AdjustKeyFactoryInterface|\Closure $adjustKey = null,
        array $options = [],
    ): ObjectCollectionInterface {
        $options['allowed_classes']     = \is_object($class) ? $class::class : $class;
        $options['create_unique_key']   = $createUniqueId;
        $options['adjust_key']          = $adjustKey;

        return new class($objects, $options) implements ObjectCollectionInterface, ObjectCollectionMagicalAccessorInterface {
            use ObjectCollectionTrait;
            use ObjectCollectionMagicalAccessorTrait;

            /**
             * @var string 受け入れ可能なクラス
             */
            private static string $allowedClasses;

            /**
             * @var UniqueIdFactoryInterface|\Closure ユニークID生成機
             */
            private static UniqueIdFactoryInterface|\Closure $createUniqueId;

            /**
             * @var null|AdjustKeyFactoryInterface|\Closure キーアジャスタ
             */
            private static null|AdjustKeyFactoryInterface|\Closure $adjustKey;

            /**
             * constructor
             *
             * @param iterable $objects 初期状態として受け入れるオブジェクトの配列
             * @param array    $options オプション
             */
            public function __construct(iterable $objects = [], array $options = [])
            {
                self::$allowedClasses   = $options['allowed_classes'];

                $createUniqueId        = $options['create_unique_key'];
                self::$createUniqueId  = $createUniqueId instanceof UniqueIdFactoryInterface ? $createUniqueId::createUniqueId(...) : $createUniqueId;

                $adjustKey              = $options['adjust_key'];
                self::$adjustKey        = $adjustKey;

                $this->options  = $options;

                foreach ($objects as $object) {
                    $this->add($object);
                }
            }

            /**
             * 受け入れ可能なクラスを返します。
             *
             * @return string 受け入れ可能なクラス
             */
            public static function getAllowedClass(): string
            {
                return self::$allowedClasses;
            }

            /**
             * 指定された値からユニークIDを返します。
             *
             * @param  mixed      $value 値
             * @return int|string ユニークID
             */
            public static function createUniqueId(mixed $value): string|int
            {
                $createUniqueId    = self::$createUniqueId;

                return $createUniqueId($value);
            }

            /**
             * キーがstring|intではなかった場合に調整して返します。
             *
             * @param  mixed       $key        キー
             * @param  null|string $access_key アクセスキー
             * @return string|int  調整済みキー
             */
            public static function adjustKey(mixed $key, ?string $access_key = null): string|int
            {
                if (self::$adjustKey === null) {
                    return $key;
                }

                $adjustKey    = self::$adjustKey;

                return $adjustKey($key);
            }
        };
    }
}
