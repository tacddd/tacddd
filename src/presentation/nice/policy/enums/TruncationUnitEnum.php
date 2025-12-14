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

namespace tacddd\presentation\nice\policy\enums;

/**
 * 省略（トランケーション）時の文字列長の測定単位。
 */
enum TruncationUnitEnum
{
    /**
     * @var string グラフェム（拡張書記素クラスタ）単位。結合文字や ZWJ による絵文字合成も 1 として扱う意図。
     */
    case GRAPHEME;

    /**
     * @var string コードポイント（Unicode スカラー値）単位。結合文字は個別にカウントされる。
     */
    case CODE_POINT;

    /**
     * @var string バイト長。UTF-8 では非 ASCII 文字が 2〜4 バイトになることがある。
     */
    case BYTE;
}
