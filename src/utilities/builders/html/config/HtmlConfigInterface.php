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

namespace tacddd\utilities\builders\html\config;

use tacddd\utilities\builders\html\safety\value_objects\HtmlSafetyRules;

/**
 * 簡易的なHTML構築ビルダ設定インターフェースです。
 */
interface HtmlConfigInterface
{
    /**
     * @var string HTMLエスケープ
     */
    public const ESCAPE_TYPE_HTML = 'html';

    /**
     * @var string JavaScriptエスケープ
     */
    public const ESCAPE_TYPE_JAVASCRIPT = 'javascript';

    /**
     * @var string JSエスケープ（互換用）
     */
    public const ESCAPE_TYPE_JS = 'js';

    /**
     * @var string CSSエスケープ
     */
    public const ESCAPE_TYPE_CSS = 'css';

    /**
     * @var string デフォルトエスケープタイプ
     */
    public const DEFAULT_ESCAPE_TYPE = self::ESCAPE_TYPE_HTML;

    /**
     * @var string デフォルトエンコーディング
     */
    public const DEFAULT_ENCODING = 'UTF-8';

    /**
     * @var string JS向けエンコーディング
     */
    public const ENCODING_FOR_JS = 'UTF-8';

    /**
     * エスケープタイプを取得・設定します。
     *
     * @param  null|string   $escape_type エスケープタイプ
     * @return string|static エスケープタイプまたはこのインスタンス
     */
    public function escapeType(?string $escape_type = null): string|static;

    /**
     * エンコーディングを取得・設定します。
     *
     * @param  null|string   $encoding エンコーディング
     * @return string|static エンコーディングまたはこのインスタンス
     */
    public function encoding(?string $encoding = null): string|static;

    /**
     * プリティファイを取得・設定します。
     *
     * @param  null|bool   $pretty_print プリティファイ
     * @return bool|static プリティファイまたはこのインスタンス
     */
    public function prettyPrint(?bool $pretty_print = null): bool|static;

    /**
     * HTMLの意図の安全性ルールを取得・設定します。
     *
     * @param  null|HtmlSafetyRules        $rules ルール
     * @return null|HtmlSafetyRules|static ルールまたはこのインスタンス
     */
    public function safetyRules(?HtmlSafetyRules $rules = null): HtmlSafetyRules|static|null;
}
