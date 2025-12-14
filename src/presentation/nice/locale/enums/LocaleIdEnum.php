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

namespace tacddd\presentation\nice\locale\enums;

/**
 * ロカール識別子を表す列挙型。
 *
 * <p>実運用で必要なものを順次追加してください。</p>
 */
enum LocaleIdEnum: string
{
    /**
     * @var string 日本語（日本）
     */
    case JA_JP = 'ja_JP';

    /**
     * @var string 英語（米国）
     */
    case EN_US = 'en_US';
}
