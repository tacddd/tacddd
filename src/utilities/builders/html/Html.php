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

use tacddd\services\utilities\builder\html\config\HtmlConfig;
use tacddd\services\utilities\builder\html\config\HtmlConfigInterface;
use tacddd\services\utilities\builder\html\traits\Htmlable;
use tacddd\utilities\containers\ContainerService;

/**
 * 簡易的なHTML構築ビルダサービス
 */
final class Html
{
    /**
     * @var HtmlConfigInterface 簡易的なHTML構築ビルダ設定
     */
    protected static ?HtmlConfigInterface $htmlConfig    = null;

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
        return HtmlElement::factory($element_name, $children, $attributes, $htmlConfig ?? self::htmlConfig());
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
        return HtmlAttribute::factory($attribute_name, $value, $htmlConfig ?? self::htmlConfig());
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
        return HtmlAttribute::factory(\sprintf('data-%s', $data_name), $value, $htmlConfig ?? self::htmlConfig());
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
        return HtmlTextNode::factory($value, $htmlConfig ?? self::htmlConfig());
    }

    /**
     * 簡易的なHTML構築ビルダ設定を取得・設定します。
     *
     * @return HtmlConfigInterface|string 簡易的なHTML構築ビルダ設定またはこのクラスパス
     */
    public static function htmlConfig($htmlConfig = null): HtmlConfigInterface|string
    {
        if ($htmlConfig === null && \func_num_args() === 0) {
            if (self::$htmlConfig === null) {
                self::$htmlConfig   = HtmlConfig::factory();
            }

            return self::$htmlConfig;
        }

        if (!($htmlConfig instanceof HtmlConfigInterface)) {
            throw new \Exception(ContainerService::getStringService()->buildDebugMessage('利用できない簡易的なHTML構築ビルダ設定を指定されました。', $htmlConfig));
        }

        self::$htmlConfig   = $htmlConfig;

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
            $encoding       = $encoding    ?? self::htmlConfig()->encoding();
            $escape_type    = $escape_type ?? self::htmlConfig()->escapetype();
        }

        return ContainerService::getStringService()->escape($value, $escape_type, [], $encoding);
    }

    /**
     * constructor
     */
    private function __construct()
    {
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

        return HtmlElement::factory($element_name, $children, $attributes, $htmlConfig ?? self::htmlConfig());
    }
}
