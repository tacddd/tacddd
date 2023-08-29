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

namespace tacddd\collections\enums;

/**
 * コレクションのキー名スタイル列挙型
 */
enum KeyStyleEnum
{
    /**
     * キーをlowerCamelCaseとして指定します。
     */
    case CamelCase;

    /**
     * キーをUpperCamelCaseとして指定します。
     */
    case UpperCamelCase;

    /**
     * キーをsnake_caseとして指定します。
     */
    case SnakeCase;

    /**
     * キーをUPPSER_SNAKE_CASEとして指定します。
     */
    case UpperSnakeCase;

    /**
     * キーをdash-caseとして指定します。
     */
    case DashCase;

    /**
     * キーをUPPER-DASH-CASEとして指定します。
     */
    case UpperDashCase;

    /**
     * ユーザコンバータを使用します。
     */
    case Converter;
}
