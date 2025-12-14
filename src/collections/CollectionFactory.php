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

namespace tacddd\collections;

use tacddd\collections\interfaces\NormalizeKeyFactoryInterface;
use tacddd\collections\interfaces\UniqueIdFactoryInterface;
use tacddd\collections\objects\ObjectCollectionFactory;
use tacddd\collections\objects\traits\ObjectCollectionInterface;

/**
 * コレクションファクトリ
 */
final class CollectionFactory
{
    /**
     * オブジェクトコレクションを生成して返します。
     *
     * @param  string|object|array                        $class          受け入れ可能なクラス
     * @param  UniqueIdFactoryInterface|\Closure          $createUniqueId ユニークID生成機
     * @param  iterable                                   $objects        初期状態で投入したいオブジェクト群
     * @param  null|NormalizeKeyFactoryInterface|\Closure $normalizeKey   キーアジャスタ
     * @param  array                                      $options        オプション
     * @return ObjectCollectionInterface                  オブジェクトコレクション
     */
    public static function createForObject(
        string|object|array $class,
        UniqueIdFactoryInterface|\Closure $createUniqueId,
        iterable $objects = [],
        null|NormalizeKeyFactoryInterface|\Closure $normalizeKey = null,
        array $options = [],
    ): ObjectCollectionInterface {
        return ObjectCollectionFactory::create(
            class           : $class,
            createUniqueId  : $createUniqueId,
            objects         : $objects,
            normalizeKey    : $normalizeKey,
            options         : $options,
        );
    }
}
