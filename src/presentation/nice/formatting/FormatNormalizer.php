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

namespace tacddd\presentation\nice\formatting;

/**
 * string|\UnitEnum|null を統一的に文字列へ正規化するユーティリティ。
 */
final class FormatNormalizer
{
    /**
     * 値を文字列へ正規化します。
     *
     * @param  null|string|\UnitEnum $value 正規化対象
     * @return null|string           正規化後の文字列（null の場合は null）
     */
    public static function toString(null|string|\UnitEnum $value): ?string
    {
        if ($value === null) {
            return null;
        }

        /** @psalm-suppress RedundantCast */
        return match (true) {
            $value instanceof \BackedEnum => (string) $value->value,
            $value instanceof \UnitEnum   => $value->name,
            \is_string($value)            => $value,
            default                       => null, // 型ガード用途（ここには通常到達しない）
        };
    }
}
