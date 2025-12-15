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
use tacddd\utilities\builders\html\elements\traits\HtmlElementInterface;
use tacddd\utilities\builders\html\elements\traits\HtmlElementTrait;
use tacddd\utilities\builders\html\traits\Htmlable;

/**
 * 簡易的なHTML要素構築ビルダです。
 */
class HtmlElement implements HtmlElementInterface
{
    use HtmlElementTrait;

    /**
     * factory
     *
     * @param  string      $element_name 要素名
     * @param  array       $attributes   属性
     * @param  array       $children     子要素
     * @return self|static このインスタンス
     */
    public static function factory(string $element_name, array $children = [], array $attributes = [], $htmlConfig = null): self|static
    {
        return new static($element_name, $children, $attributes, $htmlConfig);
    }

    /**
     * constructor
     *
     * @param  string      $element_name 要素名
     * @param  array       $attributes   属性
     * @param  array       $children     子要素
     * @return self|static このインスタンス
     */
    public function __construct(string $element_name, array $children = [], array $attributes = [], $htmlConfig = null)
    {
        $this->elementName  = $element_name;
        $this->children     = \is_array($children) ? $children : [$children];
        $this->attributes   = $attributes;
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
        $pretty = $this->htmlConfig->prettyPrint();

        $element_name = Html::escape($this->elementName, $this->htmlConfig);

        $attributes = [''];

        foreach ($this->attributes as $attribute_name => $value) {
            if (!($value instanceof HtmlAttribute)) {
                $value = $value === null
                    ? Html::attribute($attribute_name, null, $this->htmlConfig)
                    : Html::attribute($attribute_name, $value, $this->htmlConfig);
            }

            $attributes[] = $value->toHtml();
        }

        $attribute = \implode(' ', $attributes);

        if (!$pretty) {
            // 改行・インデントを一切入れない
            if (empty($this->children)) {
                return \sprintf('<%s%s>', $element_name, $attribute);
            }

            $children   = [];

            foreach ($this->children as $child) {
                if (!($child instanceof Htmlable)) {
                    $child = Html::textNode((string) $child, $this->htmlConfig);
                }

                $children[] = $child->toHtml(0);
            }

            return \sprintf(
                '<%s%s>%s</%s>',
                $element_name,
                $attribute,
                \implode('', $children),
                $element_name,
            );
        }

        $indent = \str_repeat(' ', $indent_lv * 4);

        if (!empty($this->children)) {
            $children   = [];

            if ($this->elementName === 'script') {
                return \sprintf(
                    '%s<script%s>%s%s%s</script>',
                    $indent,
                    $attribute,
                    "\n",
                    Html::escape((string) $this->children, HtmlConfigInterface::ESCAPE_TYPE_JS, HtmlConfigInterface::ENCODING_FOR_JS),
                    "\n",
                );
            }

            if (\count($this->children) === 1) {
                $use_lf = false;

                foreach ($this->children as $child) {
                    if (!($child instanceof Htmlable)) {
                        $child  = Html::textNode($child);
                    }

                    $use_lf = !($child instanceof HtmlTextNode);
                }

                return \sprintf(
                    '%s<%s%s>%s%s%s%s</%s>',
                    $indent,
                    $element_name,
                    $attribute,
                    $use_lf ? "\n" : '',
                    $use_lf ? $child->toHtml($indent_lv + 1) : $child->toHtml(0),
                    $use_lf ? "\n" : '',
                    $use_lf ? $indent : '',
                    $element_name,
                );
            }

            $before_element_html    = null;
            $child_indent           = \str_repeat(' ', ($indent_lv + 1) * 4);
            $previous_was_br        = false;

            foreach ($this->children as $child) {
                if (!($child instanceof Htmlable)) {
                    $child = Html::textNode((string) $child, $this->htmlConfig);
                }

                if ($child instanceof HtmlElement && \strcasecmp($child->elementName, 'br') === 0) {
                    $children[]             = $child->toHtml(0);
                    $children[]             = "\n";
                    $children[]             = $child_indent;
                    $before_element_html    = false;
                    $previous_was_br        = true;
                    continue;
                }

                if ($child instanceof HtmlTextNode) {
                    $children[]             = $child->toHtml($previous_was_br ? 0 : $indent_lv + 1);
                    $before_element_html    = false;
                    $previous_was_br        = false;
                    continue;
                }

                if ($before_element_html !== false) {
                    if ($previous_was_br) {
                        $last_key = \array_key_last($children);

                        if ($last_key !== null && $children[$last_key] === $child_indent) {
                            unset($children[$last_key]);
                        }

                        $previous_was_br    = false;
                    }

                    $children[] = "\n";
                }

                $children[]             = $child->toHtml($indent_lv + 1);
                $before_element_html    = true;
            }

            if ($previous_was_br) {
                $last_key = \array_key_last($children);

                if ($last_key !== null && $children[$last_key] === $child_indent) {
                    unset($children[$last_key]);
                }
            }

            $children[] = "\n";

            return \sprintf(
                '%s<%s%s>%s%s%s</%s>',
                $indent,
                $element_name,
                $attribute,
                "\n",
                \implode('', $children),
                $indent,
                $element_name,
            );
        }

        return \sprintf('%s<%s%s>', $indent, $element_name, $attribute);
    }

    /**
     * sugar factory
     *
     * @param  string      $element_name 要素名
     * @param  array       $args         引数
     * @return self|static このインスタンス
     */
    public static function __callStatic(string $element_name, array $args): self|static
    {
        $children   = $args[0] ?? [];
        $attributes = $args[1] ?? [];
        $htmlConfig = $args[2] ?? null;

        return new static($element_name, $children, $attributes, $htmlConfig);
    }
}
