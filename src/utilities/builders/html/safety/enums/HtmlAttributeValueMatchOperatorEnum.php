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

namespace tacddd\utilities\builders\html\safety\enums;

/**
 * HTML 属性値マッチ演算子 enum です。
 */
enum HtmlAttributeValueMatchOperatorEnum: string
{
    /**
     * 正規化済み文字列から enum を取得します。
     *
     * @param  string    $normalized 正規化済み（lower_snake_case想定）の演算子文字列
     * @return null|self enum または null
     */
    public static function tryFromNormalized(string $normalized): ?self
    {
        $normalized = \trim($normalized);

        if ($normalized === '') {
            return null;
        }

        return self::tryFrom($normalized);
    }

    /**
     * 前方一致です。
     */
    case Prefix = 'prefix';

    /**
     * 完全一致です。
     */
    case Equals = 'equals';

    /**
     * 部分一致です。
     */
    case Contains = 'contains';
}
