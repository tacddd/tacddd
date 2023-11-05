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

namespace tacddd\services\utilities\builder\html;

use tacddd\services\utilities\builder\html\config\HtmlConfigInterface;
use tacddd\services\utilities\builder\html\traits\Htmlable;
use tacddd\services\utilities\builder\html\traits\HtmlableTrait;

/**
 * 簡易的なHTMLテキストノード構築ビルダです。
 */
class HtmlTextNode implements Htmlable
{
    use HtmlableTrait;

    /**
     * @var string 値
     */
    protected string $value;

    /**
     * constructor
     *
     * @param  string              $value      テキスト
     * @param  HtmlConfigInterface $htmlConfig コンフィグ
     * @return self|static         このインスタンス
     */
    public static function factory(string $value, ?HtmlConfigInterface $htmlConfig = null)
    {
        return new static($value, $htmlConfig);
    }

    /**
     * constructor
     *
     * @param string              $value      テキスト
     * @param HtmlConfigInterface $htmlConfig コンフィグ
     */
    public function __construct(string $value, ?HtmlConfigInterface $htmlConfig = null)
    {
        $this->value        = $value;
        $this->htmlConfig   = $htmlConfig ?? Html::htmlConfig();
    }

    /**
     * 現在の状態を元にHTML文字列を構築し返します。
     *
     * @param  int    $indent_lv インデントレベル
     * @return string 構築したHTML文字列
     */
    public function toHtml(int $indent_lv = 0): string
    {
        return Html::escape($this->value, $this->htmlConfig);
    }
}
