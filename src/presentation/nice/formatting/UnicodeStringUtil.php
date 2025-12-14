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
 * Unicode 文字列操作のためのユーティリティ。
 */
final class UnicodeStringUtil
{
    /**
     * グラフェム（拡張書記素クラスタ）単位での長さを返します。
     *
     * @param  string $text 対象文字列（UTF-8 を想定）
     * @return int    グラフェム数
     */
    public static function graphemeLength(string $text): int
    {
        $matches = [];
        $result  = \preg_match_all('/\X/u', $text, $matches);

        if ($result === false) {
            // フォールバック（厳密ではない）
            return \mb_strlen($text);
        }

        // preg_match_all はヒット数を返す
        return $result;
    }

    /**
     * グラフェム（拡張書記素クラスタ）単位での部分切り出しを行います。
     *
     * @param  string   $text   対象文字列（UTF-8 を想定）
     * @param  int      $offset 開始位置（グラフェム単位、負数可）
     * @param  null|int $length 切り出し長（グラフェム単位、null は末尾まで、負数可）
     * @return string   切り出し結果
     */
    public static function graphemeSlice(string $text, int $offset, ?int $length): string
    {
        $matches = [];
        $ok      = \preg_match_all('/\X/u', $text, $matches);

        if ($ok === false) {
            // フォールバック（厳密ではない）
            return $length === null
             ? (\mb_substr($text, $offset) ?: '')
             : (\mb_substr($text, $offset, $length) ?: '');
        }

        $clusters = $matches[0];

        $slice = $length === null
            ? \array_slice($clusters, $offset)
            : \array_slice($clusters, $offset, $length);

        return \implode('', $slice);
    }

    /**
     * コードポイント単位での長さを返します。
     *
     * @param  string $text 対象文字列（UTF-8 を想定）
     * @return int    コードポイント数
     */
    public static function codePointLength(string $text): int
    {
        $arr = \preg_split('//u', $text, -1, \PREG_SPLIT_NO_EMPTY);

        if ($arr === false) {
            // フォールバック（厳密ではない）
            return \mb_strlen($text);
        }

        return \count($arr);
    }

    /**
     * コードポイント単位での部分切り出しを行います。
     *
     * @param  string   $text   対象文字列（UTF-8 を想定）
     * @param  int      $offset 開始位置（コードポイント単位、負数可）
     * @param  null|int $length 切り出し長（コードポイント単位、null は末尾まで、負数可）
     * @return string   切り出し結果
     */
    public static function codePointSlice(string $text, int $offset, ?int $length): string
    {
        $arr = \preg_split('//u', $text, -1, \PREG_SPLIT_NO_EMPTY);

        if ($arr === false) {
            // フォールバック（厳密ではない）
            return $length === null
                ? (\mb_substr($text, $offset) ?: '')
                : (\mb_substr($text, $offset, $length) ?: '');
        }

        $slice = $length === null
            ? \array_slice($arr, $offset)
            : \array_slice($arr, $offset, $length);

        return \implode('', $slice);
    }
}
