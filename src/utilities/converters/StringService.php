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

namespace tacddd\utilities\converters;

use tacddd\utilities\builders\DebugHtmlBuildService;
use tacddd\utilities\converters\interfaces\StringServiceInterface;

/**
 * 文字列サービス
 */
final class StringService implements StringServiceInterface
{
    // ==============================================
    // constants
    // ==============================================
    // escape
    // ----------------------------------------------
    /**
     * @var string エスケープタイプ：HTML
     */
    public const ESCAPE_TYPE_HTML       = 'html';

    /**
     * @var string エスケープタイプ：JavaScript
     */
    public const ESCAPE_TYPE_JAVASCRIPT = 'javascript';

    /**
     * @var string エスケープタイプ：JavaScript (省略形)
     */
    public const ESCAPE_TYPE_JS         = 'javascript';

    /**
     * @var string エスケープタイプ：CSS
     */
    public const ESCAPE_TYPE_CSS           = 'css';

    /**
     * @var string エスケープタイプ：シェル引数
     */
    public const ESCAPE_TYPE_SHELL         = 'shell';

    /**
     * @var int 基底となるエスケープフラグ
     */
    public const BASE_ESCAPE_FLAGS      =  \ENT_QUOTES;

    /**
     * @var array HTML関連のエスケープフラグ
     */
    public const HTML_ESCAPE_FLAGS  = [
        \ENT_HTML401,
        \ENT_HTML5,
        \ENT_XHTML,
        \ENT_XML1,
    ];

    /**
     * @var array デフォルトでの文字エンコーディング検出順序
     */
    public const DETECT_ENCODING_ORDER  = [
        'eucJP-win',
        'CP932',
        'SJIS-win',
        'JIS',
        'ISO-2022-JP',
        'UTF-8',
        'ASCII',
    ];

    /**
     * @var string JavaScript用エンコーディング
     */
    public const JAVASCRIPT_ENCODING    = 'UTF-8';

    /**
     * @var int toDebugStringにおけるデフォルトインデントレベル
     */
    public const TO_DEBUG_STRING_DEFAULT_INDENT_LEVEL  = 0;

    /**
     * @var int toDebugStringにおけるデフォルトインデント幅
     */
    public const TO_DEBUG_STRING_DEFAULT_INDENT_WIDTH  = 4;

    /**
     * @var array JS用エスケープマップ
     */
    public const JS_ESCAPE_MAP  = [
        1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
        1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
        1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
        1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
        1, 1, 1, 1, 1, 1, 1, 1, 0, 0, // 49
        0, 0, 0, 0, 0, 0, 0, 0, 1, 1,
        1, 1, 1, 1, 1, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 1, 1, 1, 1, 1, 1, 0, 0, 0, // 99
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 1, 1, 1, 1, 1, 1, 1,
        1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
        1, 1, 1, 1, 1, 1, 1, 1, 1, 1, // 149
        1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
        1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
        1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
        1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
        1, 1, 1, 1, 1, 1, 1, 1, 1, 1, // 199
        1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
        1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
        1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
        1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
        1, 1, 1, 1, 1, 1, 1, 1, 1, 1, // 249
        1, 1, 1, 1, 1, 1, 1, // 255
    ];

    /**
     * @var int JSONエンコード用デフォルトオプション
     */
    public const JSON_ENCODE_OPTIONS_DEFAULT    = \JSON_HEX_TAG | \JSON_HEX_AMP | \JSON_HEX_APOS | \JSON_HEX_QUOT;

    /**
     * @var array エスケープタイプマップ
     */
    protected array $escapeTypeMap  = [
        self::ESCAPE_TYPE_HTML          => self::ESCAPE_TYPE_HTML,
        self::ESCAPE_TYPE_JAVASCRIPT    => self::ESCAPE_TYPE_JAVASCRIPT,
        self::ESCAPE_TYPE_CSS           => self::ESCAPE_TYPE_CSS,
        self::ESCAPE_TYPE_SHELL         => self::ESCAPE_TYPE_SHELL,
    ];

    // ==============================================
    // methods
    // ==============================================
    // case conversion
    // ----------------------------------------------
    /**
     * 文字列をスネークケースに変換します。
     *
     * @param  string            $subject   スネークケースに変換する文字列
     * @param  bool              $trim      変換後に先頭の"_"をトリムするかどうか trueの場合はトリムする
     * @param  null|string|array $separator 単語の閾に用いる文字
     * @return string            スネークケースに変換された文字列
     */
    public function toSnakeCase(string $subject, bool $trim = true, string|array|null $separator = [' ', '-']): string
    {
        if ($separator !== null) {
            $subject    = \str_replace($separator, '_', $subject);
        }

        $subject    = \preg_replace(\mb_internal_encoding() === 'UTF-8' ? '/_*([A-Z])/u' : '/_*([A-Z])/', '_${1}', $subject);

        return $trim ? \ltrim($subject, '_') : $subject;
    }

    /**
     * 文字列をアッパースネークケースに変換します。
     *
     * @param  string            $subject   アッパースネークケースに変換する文字列
     * @param  bool              $trim      変換後に先頭の"_"をトリムするかどうか trueの場合はトリムする
     * @param  null|string|array $separator 単語の閾に用いる文字
     * @return string            アッパースネークケースに変換された文字列
     */
    public function toUpperSnakeCase(string $subject, bool $trim = true, string|array|null $separator = [' ', '-']): string
    {
        if ($separator !== null) {
            $subject    = \str_replace($separator, '_', $subject);
        }

        $subject    = \preg_replace(\mb_internal_encoding() === 'UTF-8' ? '/_*([A-Z])/u' : '/_*([A-Z])/', '_${1}', $subject);

        return \strtoupper($trim ? \ltrim($subject, '_') : $subject);
    }

    /**
     * 文字列をロウアースネークケースに変換します。
     *
     * @param  string            $subject   スネークケースに変換する文字列
     * @param  bool              $trim      変換後に先頭の"_"をトリムするかどうか trueの場合はトリムする
     * @param  null|string|array $separator 単語の閾に用いる文字
     * @return string            ロウアースネークケースに変換された文字列
     */
    public function toLowerSnakeCase(string $subject, bool $trim = true, string|array|null $separator = [' ', '-']): string
    {
        if ($separator !== null) {
            $subject    = \str_replace($separator, '_', $subject);
        }

        $subject    = \preg_replace(\mb_internal_encoding() === 'UTF-8' ? '/_*([A-Z])/u' : '/_*([A-Z])/', '_${1}', $subject);

        return \strtolower($trim ? \ltrim($subject, '_') : $subject);
    }

    /**
     * 文字列をチェインケースに変換します。
     *
     * @param  string            $subject   チェインケースに変換する文字列
     * @param  bool              $trim      変換後に先頭の"-"をトリムするかどうか trueの場合はトリムする
     * @param  null|string|array $separator 単語の閾に用いる文字
     * @return string            チェインケースに変換された文字列
     */
    public function toChainCase(string $subject, bool $trim = true, string|array|null $separator = [' ', '_']): string
    {
        if ($separator !== null) {
            $subject    = \str_replace($separator, '-', $subject);
        }

        $subject    = \preg_replace(\mb_internal_encoding() === 'UTF-8' ? '/\-*([A-Z])/u' : '/\-*([A-Z])/', '-${1}', $subject);

        return $trim ? \ltrim($subject, '-') : $subject;
    }

    /**
     * 文字列をアッパーチェインケースに変換します。
     *
     * @param  string            $subject   チェインケースに変換する文字列
     * @param  bool              $trim      変換後に先頭の"-"をトリムするかどうか trueの場合はトリムする
     * @param  null|string|array $separator 単語の閾に用いる文字
     * @return string            アッパーチェインケースに変換された文字列
     */
    public function toUpperChainCase(string $subject, bool $trim = true, string|array|null $separator = [' ', '_']): string
    {
        if ($separator !== null) {
            $subject    = \str_replace($separator, '-', $subject);
        }

        $subject    = \preg_replace(\mb_internal_encoding() === 'UTF-8' ? '/\-*([A-Z])/u' : '/\-*([A-Z])/', '-${1}', $subject);

        return \strtoupper($trim ? \ltrim($subject, '-') : $subject);
    }

    /**
     * 文字列をロウアーチェインケースに変換します。
     *
     * @param  string            $subject   チェインケースに変換する文字列
     * @param  bool              $trim      変換後に先頭の"-"をトリムするかどうか trueの場合はトリムする
     * @param  null|string|array $separator 単語の閾に用いる文字
     * @return string            ロウアーチェインケースに変換された文字列
     */
    public function toLowerChainCase(string $subject, bool $trim = true, string|array|null $separator = [' ', '_']): string
    {
        if ($separator !== null) {
            $subject    = \str_replace($separator, '-', $subject);
        }

        $subject    = \preg_replace(\mb_internal_encoding() === 'UTF-8' ? '/\-*([A-Z])/u' : '/\-*([A-Z])/', '-${1}', $subject);

        return \strtolower($trim ? \ltrim($subject, '-') : $subject);
    }

    /**
     * 文字列をキャメルケースに変換します。
     *
     * @param  string            $subject   キャメルケースに変換する文字列
     * @param  null|string|array $separator 単語の閾に用いる文字
     * @return string            キャメルケースに変換された文字列
     */
    public function toCamelCase(string $subject, string|array|null $separator = [' ', '-']): string
    {
        if ($separator !== null) {
            $subject    = \str_replace($separator, '_', $subject);
        }

        $subject    = \ltrim(\strtr($subject, ['_' => ' ']), ' ');

        return \strtr(\mb_substr($subject, 0, 1) . \mb_substr(\ucwords($subject), 1), [' ' => '']);
    }

    /**
     * 文字列をスネークケースからアッパーキャメルケースに変換します。
     *
     * @param  string            $subject   アッパーキャメルケースに変換する文字列
     * @param  null|string|array $separator 単語の閾に用いる文字
     * @return string            アッパーキャメルケースに変換された文字列
     */
    public function toUpperCamelCase(string $subject, string|array|null $separator = [' ', '-']): string
    {
        if ($separator !== null) {
            $subject    = \str_replace($separator, '_', $subject);
        }

        return \ucfirst(\strtr(\ucwords(\strtr($subject, ['_' => ' '])), [' ' => '']));
    }

    /**
     * 文字列をスネークケースからロウアーキャメルケースに変換します。
     *
     * @param  string            $subject   ロウアーキャメルケースに変換する文字列
     * @param  null|string|array $separator 単語の閾に用いる文字
     * @return string            ロウアーキャメルケースに変換された文字列
     */
    public function toLowerCamelCase(string $subject, string|array|null $separator = [' ', '-']): string
    {
        if ($separator !== null) {
            $subject    = \str_replace($separator, '_', $subject);
        }

        return \lcfirst(\strtr(\ucwords(\strtr($subject, ['_' => ' '])), [' ' => '']));
    }

    // ----------------------------------------------
    // escape
    // ----------------------------------------------
    /**
     * 利用可能なエスケープタイプか検証します。
     *
     * @param  string $escape_type 検証するエスケープタイプ
     * @return bool   利用可能なエスケープタイプかどうか
     */
    public function validEscapeType(string $escape_type): bool
    {
        return isset($this->escapeTypeMap[$escape_type]);
    }

    /**
     * 文字列のエスケープを行います。
     *
     * @param  string      $value    エスケープする文字列
     * @param  null|string $type     エスケープタイプ
     * @param  array       $options  オプション
     * @param  null|string $encoding エンコーディング
     * @return string      エスケープされた文字列
     */
    public function escape(string $value, ?string $type = null, array $options = [], ?string $encoding = null): string
    {
        return match ($type ?? self::ESCAPE_TYPE_HTML) {
            self::ESCAPE_TYPE_HTML          => $this->htmlEscape($value, $options, $encoding),
            self::ESCAPE_TYPE_JAVASCRIPT,
            self::ESCAPE_TYPE_JS            => $this->jsEscape($value, $options, $encoding),
            self::ESCAPE_TYPE_CSS           => $this->cssEscape($value, $options, $encoding),
            self::ESCAPE_TYPE_SHELL         => $this->shellEscape($value, $options, $encoding),
            default                         => $value,
        };
    }

    /**
     * HTML文字列のエスケープを行います。
     *
     * @param  string      $value    エスケープするHTML文字列
     * @param  array       $options  オプション
     *                               [
     *                               'flags' => array htmlspecialcharsに与えるフラグ
     *                               ]
     * @param  null|string $encoding エンコーディング
     * @return string      エスケープされたHTML文字列
     */
    public function htmlEscape(string $value, array $options = [], ?string $encoding = null): string
    {
        $encoding   = $encoding ?? \mb_internal_encoding();

        if ($encoding === 'SJIS-win') {
            $encoding = 'CP932';
        }

        if (!\mb_check_encoding($value, $encoding)) {
            throw new \InvalidArgumentException($this->buildDebugMessage('不正なエンコーディングが検出されました。', $encoding ?? \mb_internal_encoding(), \mb_detect_encoding($value, self::DETECT_ENCODING_ORDER, true)));
        }

        $flags  = self::BASE_ESCAPE_FLAGS;

        foreach (isset($options['flags']) ? (array) $options['flags'] : [] as $flag) {
            $flags  |= $flag;
        }

        $enable_html_escape_flag    = false;

        foreach (self::HTML_ESCAPE_FLAGS as $html_flag) {
            if ($enable_html_escape_flag = (0 !== $flags & $html_flag)) {
                break;
            }
        }

        if (!$enable_html_escape_flag) {
            $flags  |= \ENT_HTML5;
        }

        return \htmlspecialchars($value, $flags, $encoding);
    }

    /**
     * JavaScript文字列のエスケープを行います。
     *
     * @param  string $value   エスケープするJavaScript文字列
     * @param  array  $options オプション
     * @return string エスケープされたJavaScript文字列
     * @see https://blog.ohgaki.net/javascript-string-escape
     */
    public function jsEscape(string $value, array $options = []): string
    {
        if (!\mb_check_encoding($value, self::JAVASCRIPT_ENCODING)) {
            throw new \InvalidArgumentException($this->buildDebugMessage('不正なエンコーディングが検出されました。JavaScriptエスケープ対象の文字列はUTF-8である必要があります。', \mb_detect_encoding($value, self::DETECT_ENCODING_ORDER, true)));
        }

        // 文字エンコーディングはUTF-8
        $mblen   = \mb_strlen($value, self::JAVASCRIPT_ENCODING);
        $utf32   = \mb_convert_encoding($value, 'UTF-32', self::JAVASCRIPT_ENCODING);
        $convmap = [0x0, 0xFFFFFF, 0, 0xFFFFFF];

        for ($i = 0, $encoded = ''; $i < $mblen; ++$i) {
            // Unicodeの仕様上、最初のバイトは無視してもOK
            $c =  (\ord($utf32[$i * 4 + 1]) << 16) + (\ord($utf32[$i * 4 + 2]) << 8) + \ord($utf32[$i * 4 + 3]);

            if ($c < 256 && self::JS_ESCAPE_MAP[$c]) {
                if ($c < 0x10) {
                    $encoded .= '\\x0' . \base_convert((string) $c, 10, 16);
                } else {
                    $encoded .= '\\x' . \base_convert((string) $c, 10, 16);
                }
            } elseif ($c === 0x2028) {
                $encoded .= '\\u2028';
            } elseif ($c === 0x2029) {
                $encoded .= '\\u2029';
            } else {
                $encoded .= \mb_decode_numericentity('&#' . $c . ';', $convmap, self::JAVASCRIPT_ENCODING);
            }
        }

        return $encoded;
    }

    /**
     * CSS文字列のエスケープを行います。
     *
     * @param  string $value   エスケープするCSS文字列
     * @param  array  $options オプション
     * @return string エスケープされたCSS文字列
     * @see https://blog.ohgaki.net/css%E3%81%AE%E3%82%A8%E3%82%B9%E3%82%B1%E3%83%BC%E3%83%97%E6%96%B9%E6%B3%95
     */
    public function cssEscape(string $value, array $options = []): string
    {
        if (\is_numeric($value)) {
            return $value;
        }

        return \preg_replace_callback('/[^0-9a-z]/iSu', [self::class, 'cssEscapeConverter'], $value);
    }

    // ----------------------------------------------
    // json
    // ----------------------------------------------
    /**
     * HTML上のJavaScriptとして評価される中で安全なJSON文字列を返します。
     *
     * @param  mixed  $value JSON化する値
     * @param  int    $depth 最大の深さを設定します。正の数でなければいけません。
     * @return string JSON化された値
     */
    public function toJson(mixed $value, int $depth = 512): string
    {
        return \json_encode($value, self::JSON_ENCODE_OPTIONS_DEFAULT, $depth);
    }

    // ----------------------------------------------
    // shell
    // ----------------------------------------------
    /**
     * シェル引数のエスケープを行います。
     *
     * @param  string      $value    エスケープするシェル引数
     * @param  array       $options  オプション
     * @param  null|string $encoding エンコーディング
     * @return string      エスケープされたHTML文字列
     */
    public function shellEscape(string $value, array $options = [], ?string $encoding = null): string
    {
        $encoding   = $encoding ?? \mb_internal_encoding();

        if ($encoding === 'SJIS-win') {
            $encoding = 'CP932';
        }

        if (!\mb_check_encoding($value, $encoding)) {
            throw new \InvalidArgumentException($this->buildDebugMessage('不正なエンコーディングが検出されました。', $encoding ?? \mb_internal_encoding(), \mb_detect_encoding($value, self::DETECT_ENCODING_ORDER, true)));
        }

        return \escapeshellarg($value);
    }

    // ----------------------------------------------
    // variable
    // ----------------------------------------------
    /**
     * 簡易的なデバッグ用例外用メッセージを構築して返します。
     *
     * @param  string  $message 例外メッセージ
     * @param  mixed[] ...$vars 出力したい変数
     * @return string  デバッグ用例外メッセージ
     */
    public function buildDebugMessage(string $message, ...$vars): string
    {
        $backtrace  = \debug_backtrace()[0];
        $base_line  = $backtrace['line'];
        $tokens     = \PhpToken::tokenize(\file_get_contents($backtrace['file']));

        $in_work    = false;

        $parenthesis_stack        = 0;
        $passed_first_parenthesis = false;

        $var_names      = [];
        $sub_var_names  = [];

        foreach ($tokens as $token) {
            if ($token->line === $base_line) {
                $in_work        = true;
            }

            if (!$in_work) {
                continue;
            }

            if ($token->is(\T_COMMENT)) {
                continue;
            }

            if (!$passed_first_parenthesis) {
                if ($token->text === '(') {
                    $passed_first_parenthesis   = true;
                    ++$parenthesis_stack;
                    continue;
                }
                continue;
            }

            if ($token->text === '(') {
                $sub_var_names  = [];
                ++$parenthesis_stack;
            } elseif ($token->text === ')') {
                --$parenthesis_stack;

                if ($parenthesis_stack === 1) {
                    $last_key               = \array_key_last($var_names);
                    $var_names[$last_key]   = \sprintf('%s%s)', $var_names[$last_key], \implode('', $sub_var_names));
                    $sub_var_names          = [];
                    continue;
                }

                if ($parenthesis_stack === 0) {
                    break;
                }
            }

            if ($parenthesis_stack > 1) {
                $sub_var_names[]    = $token->text;
                continue;
            }

            if ($token->is(\T_WHITESPACE)) {
                continue;
            }

            if ($token->text === ',') {
                continue;
            }

            $var_names[] = $token->text;
        }

        $debug_strings  = [];

        foreach ($vars as $idx => $var) {
            $debug_strings[]    = \sprintf('%s:%s', $var_names[$idx + 1], self::toDebugString($var, 2));
        }

        return \sprintf('%s%s', $message, \implode(', ', $debug_strings));
    }

    /**
     * 簡易的な変数デバッグ文字列を構築して返します。
     *
     * @param  mixed  ...$vars デバッグ文字列化したい変数
     * @return string デバッグ文字列
     */
    public function buildVarsDebugString(...$vars): string
    {
        $backtrace  = \debug_backtrace()[0];
        $base_line  = $backtrace['line'];
        $tokens     = \PhpToken::tokenize(\file_get_contents($backtrace['file']));

        $in_work    = false;

        $parenthesis_stack        = 0;
        $passed_first_parenthesis = false;

        $var_names      = [];
        $sub_var_names  = [];

        foreach ($tokens as $token) {
            if ($token->line === $base_line) {
                $in_work        = true;
            }

            if (!$in_work) {
                continue;
            }

            if ($token->is(\T_COMMENT)) {
                continue;
            }

            if (!$passed_first_parenthesis) {
                if ($token->text === '(') {
                    $passed_first_parenthesis   = true;
                    ++$parenthesis_stack;
                    continue;
                }
                continue;
            }

            if ($token->text === '(') {
                $sub_var_names  = [];
                ++$parenthesis_stack;
            } elseif ($token->text === ')') {
                --$parenthesis_stack;

                if ($parenthesis_stack === 1) {
                    $last_key               = \array_key_last($var_names);
                    $var_names[$last_key]   = \sprintf('%s%s)', $var_names[$last_key], \implode('', $sub_var_names));
                    $sub_var_names          = [];
                    continue;
                }

                if ($parenthesis_stack === 0) {
                    break;
                }
            }

            if ($parenthesis_stack > 1) {
                $sub_var_names[]    = $token->text;
                continue;
            }

            if ($token->is(\T_WHITESPACE)) {
                continue;
            }

            if ($token->text === ',') {
                continue;
            }

            $var_names[] = $token->text;
        }

        $debug_strings  = [];

        foreach ($vars as $idx => $var) {
            $debug_strings[]    = \sprintf('%s:%s', $var_names[$idx], self::toDebugString($var, 2));
        }

        return \implode(', ', $debug_strings);
    }

    /**
     * 変数に関する情報を文字列にして返します。
     *
     * @param  mixed           $var     変数に関する情報を文字列にしたい変数
     * @param  int             $depth   変数に関する情報を文字列にする階層の深さ
     * @param  null|array|bool $options オプション
     *                                  [
     *                                  'prettify'      => bool     出力結果をprettifyするかどうか
     *                                  'indent_level'  => int      prettify時の開始インデントレベル
     *                                  'indent_width'  => int      prettify時のインデント幅
     *                                  'object_detail' => bool     オブジェクト詳細情報に対してのみの表示制御
     *                                  'loaded_object' => object   現時点までに読み込んだことがあるobject
     *                                  ]
     * @return string          変数に関する情報
     */
    public function toDebugString(mixed $var, int $depth = 0, array|bool|null $options = []): string
    {
        if (\is_array($options)) {
            if (!isset($options['prettify'])) {
                $options['prettify']    = isset($options['indent_level']) || isset($options['indent_width']);
            }

            if (!isset($options['indent_level'])) {
                $options['indent_level']    = $options['prettify'] ? self::TO_DEBUG_STRING_DEFAULT_INDENT_LEVEL : null;
            }

            if (!isset($options['indent_width'])) {
                $options['indent_width']    = $options['prettify'] ? self::TO_DEBUG_STRING_DEFAULT_INDENT_WIDTH : null;
            }
        } elseif (\is_bool($options) && $options) {
            $options    = [
                'prettify'      => true,
                'indent_level'  => self::TO_DEBUG_STRING_DEFAULT_INDENT_LEVEL,
                'indent_width'  => self::TO_DEBUG_STRING_DEFAULT_INDENT_WIDTH,
            ];
        } else {
            $options    = [
                'prettify'      => false,
                'indent_level'  => null,
                'indent_width'  => null,
            ];
        }

        if (!isset($options['object_detail'])) {
            $options['object_detail']   = true;
        }

        if (!isset($options['loaded_object'])) {
            $options['loaded_object']   = (object) ['loaded' => []];
        }

        switch (\gettype($var)) {
            case 'boolean':
                return $var ? 'true' : 'false';
            case 'integer':
                return (string) $var;
            case 'double':
                if (false === \mb_strpos((string) $var, '.')) {
                    return \sprintf('%s.0', $var);
                }

                return (string) $var;
            case 'string':
                return \sprintf('\'%s\'', $var);
            case 'array':
                if ($depth < 1) {
                    return 'Array';
                }
                --$depth;

                if ($options['prettify']) {
                    $next_options   = $options;

                    $tabular        = ArrayTabulatorService::create($next_options['indent_width'])->trimEolSpace(true);

                    $indent  = \str_repeat(' ', $next_options['indent_width'] * $next_options['indent_level']);

                    ++$next_options['indent_level'];

                    foreach ($var as $key => $value) {
                        $tabular->addRow([
                            $indent,
                            self::toDebugString($key),
                            \sprintf('=> %s,', self::toDebugString($value, $depth, $next_options)),
                        ]);
                    }

                    return \sprintf('[%s%s%s%s]', "\n", \implode("\n", $tabular->build()), "\n", $indent);
                }
                $ret = [];

                foreach ($var as $key => $value) {
                    $ret[] = \sprintf('%s => %s', self::toDebugString($key), self::toDebugString($value, $depth, $options));
                }

                return \sprintf('[%s]', \implode(', ', $ret));

            case 'object':
                $object_status = \sprintf('object(%s)#%d', $var::class, \spl_object_id($var));

                if ($depth < 1 || !$options['object_detail']) {
                    return $object_status;
                }

                if (isset($options['loaded_object']->loaded[$object_status])) {
                    return \sprintf('%s [displayed]', $object_status);
                }
                $options['loaded_object']->loaded[$object_status]   = $object_status;

                --$depth;

                $ro = new \ReflectionObject($var);

                $tmp_properties = [];

                foreach ($ro->getProperties() as $property) {
                    $state                               = $property->isStatic() ? 'static' : 'dynamic';
                    $modifier                            = $property->isPublic() ? 'public' : ($property->isProtected() ? 'protected' : ($property->isPrivate() ? 'private' : 'unknown modifier'));
                    $tmp_properties[$state][$modifier][] = $property;
                }

                if ($options['prettify']) {
                    $next_options   = $options;

                    $staticTabulatorService     = ArrayTabulatorService::create($next_options['indent_width'])->trimEolSpace(true);
                    $dynamicTabulatorService    = ArrayTabulatorService::create($next_options['indent_width'])->trimEolSpace(true);

                    $indent  = \str_repeat(' ', $next_options['indent_width'] * $next_options['indent_level']);

                    ++$next_options['indent_level'];

                    foreach (['static', 'dynamic'] as $state) {
                        $is_static  = $state === 'static';

                        foreach (['public', 'protected', 'private', 'unknown modifier'] as $modifier) {
                            foreach ($tmp_properties[$state][$modifier] ?? [] as $property) {
                                $property->setAccessible(true);

                                if ($is_static) {
                                    $staticTabulatorService->addRow([
                                        $indent,
                                        'static',
                                        $modifier,
                                        \sprintf('$%s', $property->getName()),
                                        \sprintf('= %s,', self::toDebugString($property->getValue($var), $depth, $next_options)),
                                    ]);
                                } else {
                                    $dynamicTabulatorService->addRow([
                                        $indent,
                                        $modifier,
                                        \sprintf('$%s', $property->getName()),
                                        \sprintf('= %s,', self::toDebugString($property->getValue($var), $depth, $next_options)),
                                    ]);
                                }
                            }
                        }
                    }

                    $rows   = [];

                    foreach ($staticTabulatorService->build() as $tab_row) {
                        $rows[] = $tab_row;
                    }

                    foreach ($dynamicTabulatorService->build() as $tab_row) {
                        $rows[] = $tab_row;
                    }

                    return \sprintf('%s {%s%s%s%s}', $object_status, "\n", \implode("\n", $rows), "\n", $indent);
                }
                $properties = [];

                foreach (['static', 'dynamic'] as $state) {
                    $state_text = $state === 'static' ? ' static' : '';

                    foreach (['public', 'protected', 'private', 'unknown modifier'] as $modifier) {
                        foreach ($tmp_properties[$state][$modifier] ?? [] as $property) {
                            $property->setAccessible(true);
                            $properties[] = \sprintf('%s%s %s = %s', $modifier, $state_text, \sprintf('$%s', $property->getName()), self::toDebugString($property->getValue($var), $depth, $options));
                        }
                    }
                }

                return \sprintf('%s {%s}', $object_status, \implode(', ', $properties));

            case 'resource':
                return \sprintf('%s %s', \get_resource_type($var), $var);
            case 'resource (closed)':
                return \sprintf('resource (closed) %s', $var);
            case 'NULL':
                return 'NULL';
            case 'unknown type':
            default:
                return 'unknown type';
        }
    }

    /**
     * 変数に関する情報をHTML出力用のビルダーとして返します。
     *
     * @param  mixed                 $var 変数に関する情報を文字列にしたい変数
     * @return DebugHtmlBuildService HTML出力用のビルダー
     */
    public function toDebugHtml(mixed $var): DebugHtmlBuildService
    {
        if (\func_num_args() === 1) {
            $instance   = DebugHtmlBuildService::factory($var)->setStartBacktraceDepth(2);
        } else {
            $instance   = \call_user_func_array([DebugHtmlBuildService::class, 'factory'], \func_get_args())->setStartBacktraceDepth(3);
        }

        return $instance;
    }

    /**
     * バイトサイズを単位付きのバイトサイズに変換します。
     *
     * @param  string|int $size      バイトサイズ
     * @param  int        $precision 小数点以下の桁数
     * @param  array      $suffixes  サフィックスマップ
     * @return string     単位付きのバイトサイズ
     */
    public function toUnitByteSize(string|int $size, int $precision = 2, array $suffixes = []): string
    {
        $base           = \log($size) / \log(1024);
        $suffixes       = \array_merge(['B', 'KB', 'MB', 'GB', 'TB'], $suffixes);
        $floored_base   = \floor($base);

        return isset($suffixes[$floored_base])
         ? \sprintf('%s%s', \round(\pow(1024, $base - \floor($base)), $precision), $suffixes[$floored_base])
         : \sprintf('%sB', \number_format($size));
    }

    /**
     * 変数をJavaScript表現として返します。
     *
     * @param  mixed  $var JavaScript表現にしたい変数
     * @return string JavaScript表現にした変数
     */
    public function toJsExpression(mixed $var): string
    {
        $type = \gettype($var);

        switch ($type) {
            case 'boolean':
                return $var ? 'true' : 'false';
            case 'integer':
                return (string) $var;
            case 'double':
                if (false === \mb_strpos((string) $var, '.')) {
                    return \sprintf('%s.0', $var);
                }

                return (string) $var;
            case 'string':
                return \sprintf('\'%s\'', $this->jsEscape($var));
            case 'array':
            case 'object':
                return self::toJson($var);
            case 'resource':
                return \sprintf('\'%s\'', $this->jsEscape(\sprintf('%s %s', \get_resource_type($var), $var)));
            case 'resource (closed)':
                return \sprintf('\'%s\'', $this->jsEscape(\sprintf('resource (closed) %s', $var)));
            case 'NULL':
                return 'null';
            case 'unknown type':
            default:
                return \sprintf('\'%s\'', $this->jsEscape('unknown type'));
        }
    }

    /**
     * フォーマットの文字列を値配列が持つ値に置き換えて返します。
     *
     * @param  string $format        文字列フォーマット
     * @param  array  $values        値
     * @param  array  $modifiers     修飾子
     * @param  array  $replacemented 変換済み変数キャッシュ
     * @return string 置き換え済みの文字列
     */
    public function buildMessage(
        string $format,
        array $values,
        array $modifiers = [],
        array $replacemented = [],
    ): string {
        if (!\str_contains($format, '${') || !\str_contains($format, '}')) {
            return $format;
        }

        $message    = \preg_replace_callback("/\\$\{([^\\$\{\}:]+)(?::([^\\$\{\}:]+))?(?:\|([^\\$\{\}\|]+))*\}/u", function($matches) use ($format, $values, $modifiers, $replacemented): string {
            $key    = $matches[1];

            if (\array_key_exists($key, $values)) {
                $replacement    = $values[$key];

                if ($replacement instanceof \Closure) {
                    $replacement    = $replacement(
                        $format,
                        $values,
                        $modifiers,
                        $replacemented,
                        $key,
                        $matches,
                        $replacement,
                    );
                }

                if (\str_contains($replacement, '${') && \str_contains($replacement, '}')) {
                    $replacement    = $this->buildMessage($replacement, $values, $replacemented);
                }
            } else {
                $replacement = $matches[2] ?? $matches[0];
            }

            if (\array_key_exists($modifier = $matches[2] ?? '', $modifiers)) {
                $modifier   = $modifiers[$modifier];

                $replacement    = $modifier($replacement);
            }

            return $replacement;
        }, $format);

        if ($format === $message) {
            return $message;
        }

        if (\str_contains($message, '${') && \str_contains($message, '}')) {
            $message    = $this->buildMessage($message, $values, $replacemented);

            if (\in_array($message, $replacemented, true)) {
                return $message;
            }

            $replacemented[]    = $message;
        }

        return $message;
    }

    /**
     * CSSエスケープ用正規表現処理結果コールバック処理
     *
     * @param  array  $matchers マッチ済みの値
     * @return string 変換後の文字
     */
    protected function cssEscapeConverter(array $matchers): string
    {
        if ($matchers[0] === "\0") {
            return '\\00FFFD';
        }

        $unpacked_char  = \unpack('Nc', \mb_convert_encoding($matchers[0], 'UTF-32BE'));

        return \sprintf(
            '\\%06X',
            $unpacked_char['c'],
        );
    }
}
