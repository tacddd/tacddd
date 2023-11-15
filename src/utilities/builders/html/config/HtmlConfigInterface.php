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

namespace tacddd\services\utilities\builder\html\config;

use tacddd\utilities\converters\StringService;

/**
 * 簡易的なHTML構築ビルダ設定インターフェースです。
 */
interface HtmlConfigInterface
{
    /**
     * @var string エスケープタイプ：HTML
     */
    public const ESCAPE_TYPE_HTML          = StringService::ESCAPE_TYPE_HTML;

    /**
     * @var string エスケープタイプ：JavaScript
     */
    public const ESCAPE_TYPE_JAVASCRIPT    = StringService::ESCAPE_TYPE_JAVASCRIPT;

    /**
     * @var string エスケープタイプ：JavaScript (省略形)
     */
    public const ESCAPE_TYPE_JS            = StringService::ESCAPE_TYPE_JS;

    /**
     * @var string エスケープタイプ：CSS
     */
    public const ESCAPE_TYPE_CSS           = StringService::ESCAPE_TYPE_CSS;

    /**
     * @var string エスケープタイプ
     */
    public const DEFAULT_ESCAPE_TYPE   = self::ESCAPE_TYPE_HTML;

    /**
     * @var string JS向けエンコーディング
     */
    public const ENCODING_FOR_JS   = 'UTF-8';

    /**
     * @var string エンコーディング
     */
    public const DEFAULT_ENCODING  = 'UTF-8';

    /**
     * ファクトリ
     *
     * @param  array       $options オプション
     * @return self|static このインスタンス
     */
    public static function factory(array $options = []): self|static;

    /**
     * エスケープタイプを取得・設定します。
     *
     * @return string エスケープタイプまたはこのクラスパス
     */
    public function escapeType($escape_type = null): string;

    /**
     * エンコーディングを取得・設定します。
     *
     * @param  null|string $encoding エンコーディング
     * @return string      エンコーディングまたはこのクラスパス
     */
    public function encoding(?string $encoding = null): string;
}
