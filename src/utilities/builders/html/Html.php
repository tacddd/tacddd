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

use tacddd\utilities\builders\html\config\HtmlConfig;
use tacddd\utilities\builders\html\config\HtmlConfigInterface;
use tacddd\utilities\builders\html\safety\value_objects\HtmlSafetyRules;
use tacddd\utilities\builders\html\traits\Htmlable;
use tacddd\utilities\builders\html\traits\HtmlableTrait;
use tacddd\utilities\containers\ContainerService;

/**
 * 簡易的なHTML構築ビルダサービスです。
 */
final class Html implements Htmlable
{
    use HtmlableTrait;

    /**
     * @var null|HtmlConfigInterface デフォルト設定
     */
    protected static ?HtmlConfigInterface $defaultHtmlConfig = null;

    /**
     * ブランクなHTML構造を返します。
     *
     * @param  array<int, Htmlable>|Htmlable $elements   要素
     * @param  null|HtmlConfigInterface      $htmlConfig 設定
     * @return self                          このインスタンス
     */
    public static function create(array|Htmlable $elements, ?HtmlConfigInterface $htmlConfig): self
    {
        return new self(
            \is_array($elements) ? $elements : [$elements],
            $htmlConfig ?? self::defaultHtmlConfig(),
        );
    }

    /**
     * 要素を返します。
     *
     * @param  string                   $element_name 要素名
     * @param  array<int, mixed>        $children     子要素
     * @param  array<string, mixed>     $attributes   属性
     * @param  null|HtmlConfigInterface $htmlConfig   設定
     * @return HtmlElement              要素
     */
    public static function element(
        string $element_name,
        array $children = [],
        array $attributes = [],
        ?HtmlConfigInterface $htmlConfig = null,
    ): HtmlElement {
        return HtmlElement::factory(
            $element_name,
            $children,
            $attributes,
            $htmlConfig ?? self::defaultHtmlConfig(),
        );
    }

    /**
     * 属性を返します。
     *
     * @param  string                   $attribute_name 属性名
     * @param  mixed                    $value          属性値
     * @param  null|HtmlConfigInterface $htmlConfig     設定
     * @return HtmlAttribute            属性
     */
    public static function attribute(string $attribute_name, mixed $value = null, ?HtmlConfigInterface $htmlConfig = null): HtmlAttribute
    {
        return HtmlAttribute::factory(
            $attribute_name,
            $value,
            $htmlConfig ?? self::defaultHtmlConfig(),
        );
    }

    /**
     * テキストノードを返します。
     *
     * @param  string                   $value      テキスト
     * @param  null|HtmlConfigInterface $htmlConfig 設定
     * @return HtmlTextNode             テキストノード
     */
    public static function textNode(string $value, ?HtmlConfigInterface $htmlConfig = null): HtmlTextNode
    {
        return HtmlTextNode::factory(
            $value,
            $htmlConfig ?? self::defaultHtmlConfig(),
        );
    }

    /**
     * 簡易的なHTML構築ビルダ設定を取得・設定します。
     *
     * @param  null|HtmlConfigInterface   $htmlConfig 設定
     * @return HtmlConfigInterface|string 設定またはクラスパス
     */
    public static function defaultHtmlConfig(?HtmlConfigInterface $htmlConfig = null): HtmlConfigInterface|string
    {
        if ($htmlConfig === null && \func_num_args() === 0) {
            if (self::$defaultHtmlConfig === null) {
                self::$defaultHtmlConfig = HtmlConfig::factory();
            }

            return self::$defaultHtmlConfig;
        }

        if (!($htmlConfig instanceof HtmlConfigInterface)) {
            throw new \Exception(ContainerService::getStringService()->buildDebugMessage('利用できない簡易的なHTML構築ビルダ設定を指定されました。', $htmlConfig));
        }

        self::$defaultHtmlConfig = $htmlConfig;

        return self::class;
    }

    /**
     * エスケープを実施します。
     *
     * @param  mixed                           $value       値
     * @param  null|string|HtmlConfigInterface $escape_type エスケープタイプ
     * @param  null|string                     $encoding    エンコーディング
     * @return string                          エスケープ済み文字列
     */
    public static function escape(mixed $value, string|HtmlConfigInterface|null $escape_type = null, ?string $encoding = null): string
    {
        if ($value instanceof Htmlable) {
            return $value->toHtml();
        }

        if ($escape_type instanceof HtmlConfigInterface) {
            $encoding    = $escape_type->encoding();
            $escape_type = $escape_type->escapeType();
        } else {
            /** @var HtmlConfigInterface $default */
            $default     = self::defaultHtmlConfig();
            $encoding    = $encoding    ?? $default->encoding();
            $escape_type = $escape_type ?? $default->escapeType();
        }

        return ContainerService::getStringService()->escape($value, $escape_type, [], $encoding);
    }

    /**
     * 与えられたHTML断片を Htmlable として再構成して返します。
     *
     * 方針：
     * - HTML断片は libxml の <html><body> 補完の影響を受けやすい。
     * - 断片専用のラッパ要素配下としてパースし、常にその配下のみを処理する。
     *
     * @param  string                   $html       HTML断片
     * @param  null|HtmlConfigInterface $htmlConfig 設定
     * @return Htmlable                 Htmlable
     */
    public static function fromHtmlFragment(
        string $html,
        ?HtmlConfigInterface $htmlConfig = null,
    ): Htmlable {
        $htmlConfig = $htmlConfig ?? self::defaultHtmlConfig();
        $rules      = $htmlConfig->safetyRules();

        $dom  = new \DOMDocument('1.0', 'UTF-8');
        $prev = \libxml_use_internal_errors(true);

        $wrapped = \sprintf(
            '<div data-tacddd-root="1">%s</div>',
            $html,
        );

        $flags = \LIBXML_NOERROR | \LIBXML_NOWARNING;

        // PHP(libxml) 環境により未定義の場合があるため、定数が存在する場合のみORします。
        if (\defined('LIBXML_HTML_NOIMPLIED')) {
            $flags |= \LIBXML_HTML_NOIMPLIED;
        }

        if (\defined('LIBXML_HTML_NODEFDTD')) {
            $flags |= \LIBXML_HTML_NODEFDTD;
        }

        $dom->loadHTML($wrapped, $flags);

        \libxml_clear_errors();
        \libxml_use_internal_errors($prev);

        $root = $dom->getElementsByTagName('div')->item(0);

        if (!$root instanceof \DOMElement) {
            return self::textNode('', $htmlConfig);
        }

        if ($rules !== null) {
            self::applySafetyRulesToDom($dom, $root, $rules);

            // 破壊的変更後のテキストノード分割などを正規化する
            $root->normalize();
        }

        $elements = [];

        $has_explicit_p_tag = \preg_match('/<\s*p\b/i', $html) === 1;

        foreach ($root->childNodes as $child) {
            // Safety 無効時のみ：互換展開（暗黙 p のフラット化）を行う
            if ($rules === null) {
                if (
                    !$has_explicit_p_tag
                    && $child instanceof \DOMElement
                    && \strcasecmp($child->tagName, 'p') === 0
                ) {
                    foreach ($child->childNodes as $pChild) {
                        $elements[] = self::fromDOMNode($pChild, $htmlConfig);
                    }
                    continue;
                }
            }

            $elements[] = self::fromDOMNode($child, $htmlConfig);
        }

        $count = \count($elements);

        if ($count === 0) {
            return self::textNode('', $htmlConfig);
        }

        if ($count === 1) {
            return $elements[0];
        }

        return self::create($elements, $htmlConfig);
    }

    /**
     * 与えられたHTMLを Htmlable として再構成して返します。
     *
     * @param  string                   $html       HTML
     * @param  null|HtmlConfigInterface $htmlConfig 設定
     * @return Htmlable                 Htmlable
     */
    public static function fromHtml(
        string $html,
        ?HtmlConfigInterface $htmlConfig = null,
    ): Htmlable {
        $htmlConfig = $htmlConfig ?? self::defaultHtmlConfig();
        $rules      = $htmlConfig->safetyRules();

        $dom  = new \DOMDocument('1.0', 'UTF-8');
        $prev = \libxml_use_internal_errors(true);

        $dom->loadHTML($html, \LIBXML_NOERROR | \LIBXML_NOWARNING);

        \libxml_clear_errors();
        \libxml_use_internal_errors($prev);

        $root = $dom->getElementsByTagName('html')->item(0);

        if ($root instanceof \DOMElement && $rules !== null) {
            self::applySafetyRulesToDom($dom, $root, $rules);
        }

        $node = $root instanceof \DOMElement ? $root : $dom->documentElement;

        if (!$node instanceof \DOMNode) {
            return self::textNode('', $htmlConfig);
        }

        return self::fromDOMNode($node, $htmlConfig);
    }

    /**
     * 与えられたDOMNodeを Htmlable として再構成して返します。
     *
     * @param  \DOMNode                 $node       DOMノード
     * @param  null|HtmlConfigInterface $htmlConfig 設定
     * @return Htmlable                 Htmlable
     */
    public static function fromDOMNode(\DOMNode $node, ?HtmlConfigInterface $htmlConfig = null): Htmlable
    {
        $htmlConfig = $htmlConfig ?? self::defaultHtmlConfig();

        if ($node instanceof \DOMText) {
            return self::textNode($node->wholeText, $htmlConfig);
        }

        if ($node instanceof \DOMElement) {
            $attributes = [];

            foreach ($node->attributes ?? [] as $attr) {
                $attributes[$attr->name] = $attr->value;
            }

            $children = [];

            foreach ($node->childNodes as $child) {
                $children[] = self::fromDOMNode($child, $htmlConfig);
            }

            return HtmlElement::factory(
                $node->tagName,
                $children,
                $attributes,
                $htmlConfig,
            );
        }

        return self::textNode('', $htmlConfig);
    }

    /**
     * DOM 上に対して意図の安全性ルールを適用します。
     *
     * @param \DOMDocument    $dom   DOM
     * @param \DOMElement     $root  適用開始要素
     * @param HtmlSafetyRules $rules ルール
     */
    private static function applySafetyRulesToDom(\DOMDocument $dom, \DOMElement $root, HtmlSafetyRules $rules): void
    {
        /**
         * 破壊的操作を行うので、後ろから処理します。
         *
         * @var array<int, \DOMElement> $elements
         */
        $elements = [];

        foreach ($root->getElementsByTagName('*') as $node) {
            if ($node instanceof \DOMElement) {
                $elements[] = $node;
            }
        }

        for ($i = \count($elements) - 1; $i >= 0; --$i) {
            $el  = $elements[$i];
            $tag = $el->tagName;

            if ($rules->shouldDropTag($tag)) {
                self::dropElement($el);
                continue;
            }

            if ($rules->shouldEscapeTag($tag)) {
                self::escapeElementAsText($dom, $el);
                continue;
            }

            if ($rules->shouldUnwrapTag($tag)) {
                self::unwrapElement($el);
                continue;
            }

            // 属性起因の昇格（drop/escape/unwrap はいずれか 1つに寄せる）
            $attributes = [];

            foreach ($el->attributes ?? [] as $attr) {
                $attributes[] = $attr;
            }

            foreach ($attributes as $attr) {
                $attrName = $attr->name;

                if ($rules->shouldDropTagWhenHasAttribute($tag, $attrName)) {
                    self::dropElement($el);
                    continue 2;
                }

                if ($rules->shouldEscapeTagWhenHasAttribute($tag, $attrName)) {
                    self::escapeElementAsText($dom, $el);
                    continue 2;
                }

                if ($rules->shouldUnwrapTagWhenHasAttribute($tag, $attrName)) {
                    self::unwrapElement($el);
                    continue 2;
                }
            }

            // 属性単位の処理
            $attributes = [];

            foreach ($el->attributes ?? [] as $attr) {
                $attributes[] = $attr;
            }

            foreach ($attributes as $attr) {
                $attrName  = $attr->name;
                $attrValue = $attr->value;

                if ($rules->shouldDropAttribute($tag, $attrName)) {
                    $el->removeAttributeNode($attr);
                    continue;
                }

                if ($rules->shouldDropAttributeWhenValue($tag, $attrName, $attrValue)) {
                    $el->removeAttributeNode($attr);
                    continue;
                }

                if ($rules->shouldEscapeAttributeWhenValue($tag, $attrName, $attrValue)) {
                    $el->setAttribute($attrName, self::escapeAttributeValue($attrValue));
                    continue;
                }
            }
        }
    }

    /**
     * 要素を削除します（タグと内容を削除）。
     *
     * @param \DOMElement $el 要素
     */
    private static function dropElement(\DOMElement $el): void
    {
        $parent = $el->parentNode;

        if ($parent instanceof \DOMNode) {
            $parent->removeChild($el);
        }
    }

    /**
     * 要素をアンラップします（タグのみ除去し内容は残す）。
     *
     * @param \DOMElement $el 要素
     */
    private static function unwrapElement(\DOMElement $el): void
    {
        $parent = $el->parentNode;

        if (!$parent instanceof \DOMNode) {
            return;
        }

        while ($el->firstChild !== null) {
            $parent->insertBefore($el->firstChild, $el);
        }

        $parent->removeChild($el);
    }

    /**
     * 要素全体をテキスト化して残します（タグ全体エスケープ）。
     *
     * @param \DOMDocument $dom DOM
     * @param \DOMElement  $el  要素
     */
    private static function escapeElementAsText(\DOMDocument $dom, \DOMElement $el): void
    {
        $parent = $el->parentNode;

        if (!$parent instanceof \DOMNode) {
            return;
        }

        $html = $dom->saveHTML($el);
        $text = $dom->createTextNode($html !== false ? $html : '');

        $parent->replaceChild($text, $el);
    }

    /**
     * 属性値をHTMLエスケープします（隔離用途）。
     *
     * @param  string $value 属性値
     * @return string エスケープ後
     */
    private static function escapeAttributeValue(string $value): string
    {
        return \htmlspecialchars($value, \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * constructor です。
     *
     * @param array<int, Htmlable>     $elements   要素
     * @param null|HtmlConfigInterface $htmlConfig 設定
     */
    private function __construct(
        private array $elements,
        ?HtmlConfigInterface $htmlConfig,
    ) {
        $this->htmlConfig = $htmlConfig;
    }

    /**
     * 現在の状態を元にHTML文字列を構築して返します。
     *
     * @param  int    $indent_lv インデントレベル
     * @return string HTML文字列
     */
    public function toHtml(int $indent_lv = 0): string
    {
        $htmlConfig = $this->htmlConfig ?? self::defaultHtmlConfig();
        $pretty     = $htmlConfig->prettyPrint();

        if (!$pretty) {
            $result = [];

            foreach ($this->elements as $element) {
                $result[] = $element->toHtml(0);
            }

            return \implode('', $result);
        }

        $indent = \str_repeat(' ', $indent_lv * 4);
        $result = [];

        foreach ($this->elements as $element) {
            $result[] = $element->toHtml($indent_lv);
        }

        return $indent . \implode($indent, $result);
    }

    /**
     * HTMLビルダを追加します。
     *
     * @param  Htmlable $html Htmlable
     * @return self     このインスタンス
     */
    public function add(Htmlable $html): self
    {
        $this->elements[] = $html;

        return $this;
    }

    /**
     * 要素を返します（マジックファクトリ）。
     *
     * @param  string      $element_name 要素名
     * @param  array       $args         引数
     * @return HtmlElement 要素
     */
    public static function __callStatic(string $element_name, array $args): HtmlElement
    {
        $children   = $args[0] ?? [];
        $attributes = $args[1] ?? [];
        $htmlConfig = $args[2] ?? null;

        return HtmlElement::factory(
            $element_name,
            $children,
            $attributes,
            $htmlConfig ?? self::defaultHtmlConfig(),
        );
    }
}
