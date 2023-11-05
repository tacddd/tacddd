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

namespace tacddd\utilities\converters\interfaces;

use tacddd\utilities\builders\DebugHtmlBuildService;

/**
 * 文字列サービスインターフェース
 */
interface StringServiceInterface
{
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
    public function toSnakeCase(string $subject, bool $trim = true, $separator = [' ', '-']): string;

    /**
     * 文字列をアッパースネークケースに変換します。
     *
     * @param  string            $subject   アッパースネークケースに変換する文字列
     * @param  bool              $trim      変換後に先頭の"_"をトリムするかどうか trueの場合はトリムする
     * @param  null|string|array $separator 単語の閾に用いる文字
     * @return string            アッパースネークケースに変換された文字列
     */
    public function toUpperSnakeCase(string $subject, bool $trim = true, $separator = [' ', '-']): string;

    /**
     * 文字列をロウアースネークケースに変換します。
     *
     * @param  string            $subject   スネークケースに変換する文字列
     * @param  bool              $trim      変換後に先頭の"_"をトリムするかどうか trueの場合はトリムする
     * @param  null|string|array $separator 単語の閾に用いる文字
     * @return string            ロウアースネークケースに変換された文字列
     */
    public function toLowerSnakeCase(string $subject, bool $trim = true, $separator = [' ', '-']): string;

    /**
     * 文字列をチェインケースに変換します。
     *
     * @param  string            $subject   チェインケースに変換する文字列
     * @param  bool              $trim      変換後に先頭の"-"をトリムするかどうか trueの場合はトリムする
     * @param  null|string|array $separator 単語の閾に用いる文字
     * @return string            チェインケースに変換された文字列
     */
    public function toChainCase(string $subject, bool $trim = true, $separator = [' ', '_']): string;

    /**
     * 文字列をアッパーチェインケースに変換します。
     *
     * @param  string            $subject   チェインケースに変換する文字列
     * @param  bool              $trim      変換後に先頭の"-"をトリムするかどうか trueの場合はトリムする
     * @param  null|string|array $separator 単語の閾に用いる文字
     * @return string            アッパーチェインケースに変換された文字列
     */
    public function toUpperChainCase(string $subject, bool $trim = true, $separator = [' ', '_']): string;

    /**
     * 文字列をロウアーチェインケースに変換します。
     *
     * @param  string            $subject   チェインケースに変換する文字列
     * @param  bool              $trim      変換後に先頭の"-"をトリムするかどうか trueの場合はトリムする
     * @param  null|string|array $separator 単語の閾に用いる文字
     * @return string            ロウアーチェインケースに変換された文字列
     */
    public function toLowerChainCase(string $subject, bool $trim = true, $separator = [' ', '_']): string;

    /**
     * 文字列をキャメルケースに変換します。
     *
     * @param  string            $subject   キャメルケースに変換する文字列
     * @param  null|string|array $separator 単語の閾に用いる文字
     * @return string            キャメルケースに変換された文字列
     */
    public function toCamelCase(string $subject, $separator = [' ', '-']): string;

    /**
     * 文字列をスネークケースからアッパーキャメルケースに変換します。
     *
     * @param  string            $subject   アッパーキャメルケースに変換する文字列
     * @param  null|string|array $separator 単語の閾に用いる文字
     * @return string            アッパーキャメルケースに変換された文字列
     */
    public function toUpperCamelCase(string $subject, $separator = [' ', '-']): string;

    /**
     * 文字列をスネークケースからロウアーキャメルケースに変換します。
     *
     * @param  string            $subject   ロウアーキャメルケースに変換する文字列
     * @param  null|string|array $separator 単語の閾に用いる文字
     * @return string            ロウアーキャメルケースに変換された文字列
     */
    public function toLowerCamelCase(string $subject, $separator = [' ', '-']): string;

    // ----------------------------------------------
    // escape
    // ----------------------------------------------
    /**
     * 利用可能なエスケープタイプか検証します。
     *
     * @param  string $escape_type 検証するエスケープタイプ
     * @return bool   利用可能なエスケープタイプかどうか
     */
    public function validEscapeType(string $escape_type): bool;

    /**
     * 文字列のエスケープを行います。
     *
     * @param  string      $value    エスケープする文字列
     * @param  null|string $type     エスケープタイプ
     * @param  array       $options  オプション
     * @param  null|string $encoding エンコーディング
     * @return string      エスケープされた文字列
     */
    public function escape(string $value, ?string $type = null, array $options = [], ?string $encoding = null): string;

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
    public function htmlEscape(string $value, array $options = [], ?string $encoding = null): string;

    /**
     * JavaScript文字列のエスケープを行います。
     *
     * @param  string $value   エスケープするJavaScript文字列
     * @param  array  $options オプション
     * @return string エスケープされたJavaScript文字列
     * @see https://blog.ohgaki.net/javascript-string-escape
     */
    public function jsEscape(string $value, array $options = []): string;

    /**
     * CSS文字列のエスケープを行います。
     *
     * @param  string $value   エスケープするCSS文字列
     * @param  array  $options オプション
     * @return string エスケープされたCSS文字列
     * @see https://blog.ohgaki.net/css%E3%81%AE%E3%82%A8%E3%82%B9%E3%82%B1%E3%83%BC%E3%83%97%E6%96%B9%E6%B3%95
     */
    public function cssEscape(string $value, array $options = []): string;

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
    public function toJson($value, int $depth = 512): string;

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
    public function shellEscape(string $value, array $options = [], ?string $encoding = null): string;

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
    public function buildDebugMessage(string $message, ...$vars): string;

    /**
     * 簡易的な変数デバッグ文字列を構築して返します。
     *
     * @param  mixed  ...$vars デバッグ文字列化したい変数
     * @return string デバッグ文字列
     */
    public function buildVarsDebugString(...$vars): string;

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
    public function toDebugString($var, int $depth = 0, $options = []): string;

    /**
     * 変数に関する情報をHTML出力用のビルダーとして返します。
     *
     * @param  mixed                 $var 変数に関する情報を文字列にしたい変数
     * @return DebugHtmlBuildService HTML出力用のビルダー
     */
    public function toDebugHtml($var): DebugHtmlBuildService;

    /**
     * バイトサイズを単位付きのバイトサイズに変換します。
     *
     * @param  string|int $size      バイトサイズ
     * @param  int        $precision 小数点以下の桁数
     * @param  array      $suffixes  サフィックスマップ
     * @return string     単位付きのバイトサイズ
     */
    public function toUnitByteSize($size, int $precision = 2, array $suffixes = []): string;

    /**
     * 変数をJavaScript表現として返します。
     *
     * @param  mixed  $var JavaScript表現にしたい変数
     * @return string JavaScript表現にした変数
     */
    public function toJsExpression($var): string;
}
