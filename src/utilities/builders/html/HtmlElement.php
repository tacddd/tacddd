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
 * 簡易的なHTML要素です。
 */
final class HtmlElement implements Htmlable
{
    use HtmlableTrait;

    /**
     * @var string 要素名
     */
    private string $elementName;

    /**
     * @var array<int, Htmlable> 子要素
     */
    private array $children;

    /**
     * @var array<string, mixed> 属性
     */
    private array $attributes;

    /**
     * ファクトリです。
     *
     * @param  string                   $element_name 要素名
     * @param  array<int, Htmlable>     $children     子要素
     * @param  array<string, mixed>     $attributes   属性
     * @param  null|HtmlConfigInterface $htmlConfig   設定
     * @return self                     要素
     */
    public static function factory(
        string $element_name,
        array $children = [],
        array $attributes = [],
        ?HtmlConfigInterface $htmlConfig = null,
    ): self {
        $instance = new self($element_name, $children, $attributes);
        $instance->htmlConfig($htmlConfig ?? Html::defaultHtmlConfig());

        return $instance;
    }

    /**
     * インデント文字列を返します。
     *
     * @param  int    $indent_lv インデントレベル
     * @return string インデント
     */
    private static function indent(int $indent_lv): string
    {
        return \str_repeat(' ', $indent_lv * 4);
    }

    /**
     * void要素かどうかを返します。
     *
     * @param  string $tag タグ名
     * @return bool   void要素ならtrue
     */
    private static function isVoidTag(string $tag): bool
    {
        return \in_array($tag, ['br', 'hr', 'img', 'input', 'meta', 'link', 'source', 'area', 'base', 'col', 'embed', 'param', 'track', 'wbr'], true);
    }

    /**
     * inline要素かどうかを返します。
     *
     * @param  string $tag タグ名
     * @return bool   inline要素ならtrue
     */
    private static function isInlineTag(string $tag): bool
    {
        return \in_array($tag, ['a', 'span', 'em', 'strong', 'b', 'i', 'u', 'small', 'code', 'kbd', 'samp', 'sub', 'sup', 'br'], true);
    }

    /**
     * constructor です。
     *
     * @param string               $elementName 要素名
     * @param array<int, Htmlable> $children    子要素
     * @param array<string, mixed> $attributes  属性
     */
    private function __construct(string $elementName, array $children, array $attributes)
    {
        $this->elementName = $elementName;
        $this->children    = $children;
        $this->attributes  = $attributes;
    }

    /**
     * 属性を設定します。
     *
     * @param  string $name  属性名
     * @param  mixed  $value 属性値
     * @return $this  このインスタンス
     */
    public function attr(string $name, mixed $value = null): self
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * 子要素を追加します。
     *
     * @param  Htmlable $child 子要素
     * @return $this    このインスタンス
     */
    public function appendChildNode(Htmlable $child): self
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * 現在の状態を元にHTML文字列を構築して返します。
     *
     * @param  int    $indent_lv インデントレベル
     * @return string HTML文字列
     */
    public function toHtml(int $indent_lv = 0): string
    {
        $config = $this->htmlConfig();
        $pretty = $config?->prettyPrint() ?? false;

        $tag = $this->normalizedTagName();

        if (!$pretty) {
            return $this->toHtmlCompact();
        }

        // inline要素は prettyPrint 有効でも常に 1行（compact）で返します。
        if (self::isInlineTag($tag)) {
            return $this->toHtmlCompact();
        }

        return $this->toHtmlPretty($indent_lv);
    }

    /**
     * コンパクトにHTMLを出力します。
     *
     * @return string HTML文字列
     */
    private function toHtmlCompact(): string
    {
        $tag = $this->normalizedTagName();

        $attrs = $this->buildAttributesString();
        $open  = $attrs === '' ? \sprintf('<%s>', $tag) : \sprintf('<%s %s>', $tag, $attrs);

        if (self::isVoidTag($tag)) {
            return $open;
        }

        $children = [];

        foreach ($this->children as $child) {
            $children[] = $child->toHtml(0);
        }

        return \sprintf('%s%s</%s>', $open, \implode('', $children), $tag);
    }

    /**
     * prettyPrint 用にHTMLを出力します。
     *
     * @param  int    $indent_lv インデントレベル
     * @return string HTML文字列
     */
    private function toHtmlPretty(int $indent_lv): string
    {
        $tag         = $this->normalizedTagName();
        $indent      = self::indent($indent_lv);
        $childIndent = self::indent($indent_lv + 1);

        $attrs = $this->buildAttributesString();
        $open  = $attrs === '' ? \sprintf('<%s>', $tag) : \sprintf('<%s %s>', $tag, $attrs);

        if (self::isVoidTag($tag)) {
            return $indent . $open;
        }

        if ($this->children === []) {
            return \sprintf('%s%s</%s>', $indent, $open, $tag);
        }

        $lines = [];
        $line  = $childIndent;

        foreach ($this->children as $child) {
            if ($child instanceof HtmlTextNode) {
                $line .= $child->toHtml(0);
                continue;
            }

            if ($child instanceof self) {
                $childTag = $child->normalizedTagName();

                // inline は同一行へ連結（br だけは行を確定）
                if (self::isInlineTag($childTag)) {
                    $childHtml = $child->toHtml(0);

                    if ($childTag === 'br') {
                        $line .= $childHtml;
                        $lines[] = $line;
                        $line    = $childIndent;
                        continue;
                    }

                    $line .= $childHtml;
                    continue;
                }
            }

            if ($line !== $childIndent) {
                $lines[] = $line;
                $line    = $childIndent;
            }

            $childHtml = $child->toHtml($indent_lv + 1);
            $lines     = \array_merge($lines, \explode("\n", $childHtml));
        }

        if ($line !== $childIndent) {
            $lines[] = $line;
        }

        return \implode(
            "\n",
            \array_merge(
                [$indent . $open],
                $lines,
                [\sprintf('%s</%s>', $indent, $tag)],
            ),
        );
    }

    /**
     * 属性文字列を構築します。
     *
     * @return string 属性文字列
     */
    private function buildAttributesString(): string
    {
        if ($this->attributes === []) {
            return '';
        }

        $pairs = [];

        foreach ($this->attributes as $name => $value) {
            if ($value === null) {
                $pairs[] = $name;
                continue;
            }

            $escaped = Html::escape((string) $value, $this->htmlConfig());
            $pairs[] = \sprintf('%s="%s"', $name, $escaped);
        }

        return \implode(' ', $pairs);
    }

    /**
     * 正規化済みタグ名を返します。
     *
     * @return string タグ名
     */
    private function normalizedTagName(): string
    {
        return \strtolower($this->elementName);
    }
}
