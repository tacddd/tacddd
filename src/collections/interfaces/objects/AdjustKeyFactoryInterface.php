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

namespace tacddd\collections\interfaces\objects;

/**
 * オブジェクトコレクション向けキーアジャスタ構築インターフェース
 */
interface AdjustKeyFactoryInterface
{
    /**
     * キーがstring|intではなかった場合に調整して返します。
     *
     * @param  mixed      $key キー
     * @return string|int 調整済みキー
     */
    public static function adjustKey(mixed $key): string|int;
}
