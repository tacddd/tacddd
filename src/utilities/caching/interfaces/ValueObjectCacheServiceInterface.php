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

namespace tacddd\utilities\caching\interfaces;

use tacddd\value_objects\ValueObjectInterface;

/**
 * 値オブジェクトキャッシュサービスインターフェース
 */
interface ValueObjectCacheServiceInterface
{
    /**
     * キャッシュ可能なfromString。
     *
     * $valueObjectが値オブジェクトの場合、$valueと$argsから算出したキャッシュキーを元に$valueObjectをキャッシュします。
     *
     * $valueObjectがクラスパス文字列の場合、指定された文字列から値オブジェクトを生成します。
     * キャッシュにすでに該当する値オブジェクトが存在する場合は、それを返します。
     * 存在しない場合は、新たに値オブジェクトを生成してキャッシュし、それを返します。
     *
     * @param  ValueObjectInterface|string $valueObject キャッシュ対象のオブジェクトまたはクラス
     * @param  string                      $value       fromString値
     * @param  array                       ...$args     追加の引数
     * @return ValueObjectInterface        生成された（またはキャッシュから取得された）値オブジェクト
     */
    public function cacheableFromString(ValueObjectInterface|string $valueObject, string $value, ...$args): ValueObjectInterface;
}
