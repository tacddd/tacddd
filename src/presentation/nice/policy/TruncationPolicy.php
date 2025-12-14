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

namespace tacddd\presentation\nice\policy;

use tacddd\presentation\nice\formatting\UnicodeStringUtil;
use tacddd\presentation\nice\policy\enums\EllipsisEnum;
use tacddd\presentation\nice\policy\enums\TruncationUnitEnum;

/**
 * 文字列の最大幅や省略記号の付与など、簡易な省略方針（Truncation）を表現します。
 */
final class TruncationPolicy
{
    /**
     * @var int 表示最大幅（この幅を超える場合に省略）
     */
    public readonly int $maxWidth;

    /**
     * @var TruncationUnitEnum 幅測定単位（GRAPHEME / CODE_POINT / BYTE）
     */
    public readonly TruncationUnitEnum $unit;

    /**
     * @var EllipsisEnum 省略記号
     */
    public readonly EllipsisEnum $ellipsis;

    /**
     * コンストラクタ。
     *
     * @param int                $maxWidth 表示最大幅（この幅を超える場合に省略）
     * @param TruncationUnitEnum $unit     幅測定単位（GRAPHEME / CODE_POINT / BYTE）
     * @param EllipsisEnum       $ellipsis 省略記号
     */
    public function __construct(
        int $maxWidth = 80,
        TruncationUnitEnum $unit = TruncationUnitEnum::CODE_POINT,
        EllipsisEnum $ellipsis = EllipsisEnum::SINGLE,
    ) {
        $this->maxWidth = $maxWidth;
        $this->unit     = $unit;
        $this->ellipsis = $ellipsis;
    }

    /**
     * 省略方針に従って文字列を加工します。
     *
     * @param  string $text 入力文字列（UTF-8 を想定）
     * @return string 加工後の文字列
     */
    public function apply(string $text): string
    {
        if ($this->length($text) <= $this->maxWidth) {
            return $text;
        }

        $max  = \max(0, $this->maxWidth - $this->ellipsisLength());
        $head = $this->slice($text, 0, $max);

        return $head . $this->ellipsis->value;
    }

    /**
     * 省略記号の長さを測定単位に基づいて算出します。
     *
     * @return int 省略記号の長さ
     */
    private function ellipsisLength(): int
    {
        return match ($this->unit) {
            TruncationUnitEnum::BYTE       => \strlen($this->ellipsis->value),
            TruncationUnitEnum::GRAPHEME   => UnicodeStringUtil::graphemeLength($this->ellipsis->value),
            TruncationUnitEnum::CODE_POINT => UnicodeStringUtil::codePointLength($this->ellipsis->value),
        };
    }

    /**
     * 文字列長を測定単位に基づいて算出します。
     *
     * @param  string $text 入力文字列（UTF-8 を想定）
     * @return int    長さ
     */
    private function length(string $text): int
    {
        return match ($this->unit) {
            TruncationUnitEnum::BYTE       => \strlen($text),
            TruncationUnitEnum::GRAPHEME   => UnicodeStringUtil::graphemeLength($text),
            TruncationUnitEnum::CODE_POINT => UnicodeStringUtil::codePointLength($text),
        };
    }

    /**
     * 文字列の一部を測定単位に基づいて切り出します。
     *
     * <p>$offset / $length は負数も許容します。</p>
     *
     * @param  string   $text   入力文字列（UTF-8 を想定）
     * @param  int      $offset 開始位置
     * @param  null|int $length 切り出し長（null の場合は末尾まで）
     * @return string   切り出し結果
     */
    private function slice(string $text, int $offset, ?int $length): string
    {
        return match ($this->unit) {
            TruncationUnitEnum::BYTE       => $length === null
             ? (\substr($text, $offset) ?: '')
             : (\substr($text, $offset, $length) ?: ''),
            TruncationUnitEnum::GRAPHEME   => UnicodeStringUtil::graphemeSlice($text, $offset, $length),
            TruncationUnitEnum::CODE_POINT => UnicodeStringUtil::codePointSlice($text, $offset, $length),
        };
    }
}
