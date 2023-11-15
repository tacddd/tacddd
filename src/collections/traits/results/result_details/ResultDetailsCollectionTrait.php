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

namespace tacddd\collections\traits\results\result_details;

/**
 * 結果詳細コレクション特性
 */
trait ResultDetailsCollectionTrait
{
    /**
     * 受け入れ可能なクラスを返します。
     *
     * @return string|array 受け入れ可能なクラス
     */
    public static function getAllowedClass(): string
    {
        return ResultDetailsInterface::class;
    }

    /**
     * 指定されたオブジェクトからユニークキーを返します。
     *
     * @param  ResultDetailsInterface $element オブジェクト
     * @return int|string             ユニークキー
     */
    public static function createUniqueId(mixed $element): string|int
    {
        return \spl_object_id($element);
    }
}
