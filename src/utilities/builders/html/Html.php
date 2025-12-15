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
use tacddd\utilities\builders\html\traits\Htmlable;
use tacddd\utilities\builders\html\traits\HtmlableTrait;
use tacddd\utilities\containers\ContainerService;

/**
 * 簡易的なHTML構築ビルダサービス
 */
final class Html implements Htmlable
{
    use HtmlableTrait;

    /**
     * @var HtmlConfigInterface 簡易的なHTML構築ビルダ設定
     */
    protected static ?HtmlConfigInterface $defaultHtmlConfig    = null;

    /**
     * ブランクなHTML構造を返します。
     *
     * @return self このインスタンス
     */
    public static function create(
        array|Htmlable $elements,
        ?HtmlConfigInterface $htmlConfig,
    ): self {
        return new self(
            \is_array($elements) ? $elements : [$elements],
            $htmlConfig ?? self::defaultHtmlConfig(),
        );
    }

    /**
     * 要素を返します。
     *
     * @param  string              $element_name 要素名
     * @param  array               $children     子要素
     * @param  array               $attributes   属性
     * @param  HtmlConfigInterface $htmlConfig   コンフィグ
     * @return HtmlElement         要素
     */
    public static function element(string $element_name, array $children = [], array $attributes = [], ?HtmlConfigInterface $htmlConfig = null): HtmlElement
    {
        return HtmlElement::factory($element_name, $children, $attributes, $htmlConfig ?? self::defaultHtmlConfig());
    }

    /**
     * 属性を返します。
     *
     * @param  string              $attribute_name 属性名
     * @param  mixed               $value          属性値
     * @param  HtmlConfigInterface $htmlConfig     コンフィグ
     * @return HtmlAttribute       属性
     */
    public static function attribute(string $attribute_name, mixed $value = null, ?HtmlConfigInterface $htmlConfig = null): HtmlAttribute
    {
        return HtmlAttribute::factory($attribute_name, $value, $htmlConfig ?? self::defaultHtmlConfig());
    }

    /**
     * データ属性を返します。
     *
     * @param  string              $data_name  データ属性名
     * @param  mixed               $value      属性値
     * @param  HtmlConfigInterface $htmlConfig コンフィグ
     * @return HtmlAttribute       属性
     */
    public static function data(string $data_name, mixed $value = null, ?HtmlConfigInterface $htmlConfig = null): HtmlAttribute
    {
        return HtmlAttribute::factory(\sprintf('data-%s', $data_name), $value, $htmlConfig ?? self::defaultHtmlConfig());
    }

    /**
     * テキストノードを返します。
     *
     * @param  string              $value      テキスト
     * @param  HtmlConfigInterface $htmlConfig コンフィグ
     * @return HtmlTextNode        テキストノード
     */
    public static function textNode(string $value, ?HtmlConfigInterface $htmlConfig = null): HtmlTextNode
    {
        return HtmlTextNode::factory($value, $htmlConfig ?? self::defaultHtmlConfig());
    }

    /**
     * 簡易的なHTML構築ビルダ設定を取得・設定します。
     *
     * @return HtmlConfigInterface|string 簡易的なHTML構築ビルダ設定またはこのクラスパス
     */
    public static function defaultHtmlConfig($htmlConfig = null): HtmlConfigInterface|string
    {
        if ($htmlConfig === null && \func_num_args() === 0) {
            if (self::$defaultHtmlConfig === null) {
                self::$defaultHtmlConfig    = HtmlConfig::factory();
            }

            return self::$defaultHtmlConfig;
        }

        if (!($htmlConfig instanceof HtmlConfigInterface)) {
            throw new \Exception(ContainerService::getStringService()->buildDebugMessage('利用できない簡易的なHTML構築ビルダ設定を指定されました。', $htmlConfig));
        }

        self::$defaultHtmlConfig    = $htmlConfig;

        return self::class;
    }

    /**
     * エスケープを実施します。
     *
     * @param  mixed                      $value       値
     * @param  string|HtmlConfigInterface $escape_type エスケープタイプ
     * @param  null|string                $encoding    エンコーディング
     * @return string                     エスケープ済みの値
     */
    public static function escape(mixed $value, string|HtmlConfigInterface|null $escape_type = null, ?string $encoding = null): string
    {
        if ($value instanceof Htmlable) {
            return $value->toHtml();
        }

        if ($escape_type instanceof HtmlConfigInterface) {
            $encoding       = $escape_type->encoding();
            $escape_type    = $escape_type->escapetype();
        } else {
            $encoding       = $encoding    ?? self::defaultHtmlConfig()->encoding();
            $escape_type    = $escape_type ?? self::defaultHtmlConfig()->escapetype();
        }

        return ContainerService::getStringService()->escape($value, $escape_type, [], $encoding);
    }

    /**
     * 与えられたHTML断片をHtmlableオブジェクトとして再構成して返します。
     *
     * @param  string   $html HTML
     * @return Htmlable Htmlableオブジェクト
     */
    public static function fromHtmlFragment(string $html, ?HtmlConfigInterface $htmlConfig = null): Htmlable
    {
        $htmlConfig = $htmlConfig ?? self::defaultHtmlConfig();

        $dom = new \DOMDocument('1.0', 'UTF-8');

        $prev = \libxml_use_internal_errors(true);

        $dom->loadHTML(
            '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $html,
            \LIBXML_NOERROR | \LIBXML_NOWARNING,
        );

        \libxml_clear_errors();
        \libxml_use_internal_errors($prev);

        $body = $dom->getElementsByTagName('body')->item(0);

        if (!$body instanceof \DOMElement) {
            return self::textNode('', $htmlConfig);
        }

        $elements = [];

        $has_explicit_p_tag = \preg_match('/<\s*p\b/i', $html) === 1;

        foreach ($body->childNodes as $child) {
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
     * 与えられたHTMLをHtmlableオブジェクトとして再構成して返します。
     *
     * @param  string   $html HTML
     * @return Htmlable Htmlableオブジェクト
     */
    public static function fromHtml(string $html, ?HtmlConfigInterface $htmlConfig = null): Htmlable
    {
        $htmlConfig = $htmlConfig ?? self::defaultHtmlConfig();

        $dom = new \DOMDocument('1.0', 'UTF-8');

        $prev = \libxml_use_internal_errors(true);

        $dom->loadHTML($html, \LIBXML_NOERROR | \LIBXML_NOWARNING);

        \libxml_clear_errors();
        \libxml_use_internal_errors($prev);

        return self::fromDOMNode($dom->documentElement, $htmlConfig);
    }

    /**
     * 与えられたDOMNodeをHtmlableオブジェクトとして再構成して返します。
     *
     * @return Htmlable Htmlableオブジェクト
     */
    public static function fromDOMNode(\DOMNode $node, ?HtmlConfigInterface $htmlConfig = null): Htmlable
    {
        $htmlConfig = $htmlConfig ?? self::defaultHtmlConfig();

        if ($node instanceof \DOMText) {
            return self::textNode($node->wholeText, $htmlConfig);
        }

        if ($node instanceof \DOMElement) {
            /** @var HtmlElement $html */
            $html = self::{$node->tagName}([], [], $htmlConfig);

            foreach ($node->attributes ?? [] as $attr) {
                $html->attr($attr->name, $attr->value);
            }

            foreach ($node->childNodes as $child) {
                $html->appendChildNode(
                    self::fromDOMNode($child, $htmlConfig),
                );
            }

            return $html;
        }

        return self::textNode('', $htmlConfig);
    }

    /**
     * constructor
     */
    private function __construct(
        private array $elements,
        ?HtmlConfigInterface $htmlConfig,
    ) {
        $this->htmlConfig   = $htmlConfig;
    }

    /**
     * 現在の状態を元にHTML文字列を構築し返します。
     *
     * @param  int    $indent_lv インデントレベル
     * @return string 構築したHTML文字列
     */
    public function toHtml(int $indent_lv = 0): string
    {
        $result = [];

        foreach ($this->elements as $element) {
            /** @var Htmlable $element */
            $result[]   = $element->toHtml($indent_lv);
        }

        return \implode('', $result);
    }

    /**
     * HTMLビルダを追加します。
     *
     * @param  Htmlable $html HTMLビルダ
     * @return self     このインスタンス
     */
    public function add(Htmlable $html): self
    {
        $this->elements[]   = $html;

        return $this;
    }

    /**
     * 要素を返します。
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

        return HtmlElement::factory($element_name, $children, $attributes, $htmlConfig ?? self::defaultHtmlConfig());
    }
}
