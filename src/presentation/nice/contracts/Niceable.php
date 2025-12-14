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

namespace tacddd\presentation\nice\contracts;

/**
 * 「人間可読（Nice）」な文字列表現を提供する契約インターフェース。
 */
interface Niceable
{
    /**
     * 人間が読みやすい文字列へ変換して返します。
     *
     * @param  null|string|\UnitEnum $format フォーマット
     * @return string                人間可読（nice）な文字列表現
     */
    public function nice(null|string|\UnitEnum $format = null): string;
}
