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
 * 省略記号の定義。
 */
enum EllipsisEnum: string
{
    /**
     * @var string 一点リーダ（Unicode '…'）
     */
    case SINGLE = '…';

    /**
     * @var string ピリオド三つ（"..."）
     */
    case THREE_DOTS = '...';

    /**
     * @var string 省略記号なし（空文字）
     */
    case NONE = '';
}
