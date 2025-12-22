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

namespace tacddd\utilities\builders\html;

use tacddd\utilities\builders\html\config\HtmlConfigInterface;
use tacddd\utilities\builders\html\traits\Htmlable;
use tacddd\utilities\builders\html\traits\HtmlableTrait;

/**
 * 簡易的なHTMLテキストノードです。
 */
final class HtmlTextNode implements Htmlable
{
    use HtmlableTrait;

    /**
     * @var string テキスト
     */
    private string $value;

    /**
     * ファクトリです。
     *
     * @param  string                   $value      テキスト
     * @param  null|HtmlConfigInterface $htmlConfig 設定
     * @return self                     テキストノード
     */
    public static function factory(string $value, ?HtmlConfigInterface $htmlConfig = null): self
    {
        $instance = new self($value);
        $instance->htmlConfig($htmlConfig ?? Html::defaultHtmlConfig());

        return $instance;
    }

    /**
     * constructor です。
     *
     * @param string $value テキスト
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * 現在の状態を元にHTML文字列を構築して返します。
     *
     * @param  int    $indent_lv インデントレベル
     * @return string HTML文字列
     */
    public function toHtml(int $indent_lv = 0): string
    {
        return Html::escape($this->value, $this->htmlConfig());
    }
}
