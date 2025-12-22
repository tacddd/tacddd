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

namespace tacddd\utilities\builders\html\safety\builders;

use tacddd\utilities\builders\html\safety\enums\HtmlAttributeValueMatchOperatorEnum;
use tacddd\utilities\builders\html\safety\value_objects\HtmlSafetyRules;

/**
 * HTML の意図の安全性ルールを構築するビルダです。
 *
 * - ルールは複数系統（タグ drop/タグ escape/タグ unwrap/属性 drop/昇格/属性値条件）を並立して設定可能です。
 * - タグ名は `*` を指定することで任意タグを対象にできます。
 * - 属性名は `on*` のように末尾 `*` を付けることで prefix 指定できます。
 *
 * 重要：
 * - 属性値マッチ演算子は enum 前提です。
 */
final class HtmlSafetyRulesBuilder
{
    /**
     * @var array<string, bool> タグ drop
     */
    private array $dropTags = [];

    /**
     * @var array<string, bool> タグ escape
     */
    private array $escapeTags = [];

    /**
     * @var array<string, bool> タグ unwrap
     */
    private array $unwrapTags = [];

    /**
     * @var array<string, array<string, bool>> 属性 drop（完全一致） tag => attr => true
     */
    private array $dropAttributesExact = [];

    /**
     * @var array<string, array<string, bool>> 属性 drop（prefix） tag => prefix => true
     */
    private array $dropAttributesPrefix = [];

    /**
     * @var array<string, array<string, bool>> 属性があればタグ drop に昇格 tag => attrSpec => true
     */
    private array $dropTagsWhenHasAttributes = [];

    /**
     * @var array<string, array<string, bool>> 属性があればタグ escape に昇格 tag => attrSpec => true
     */
    private array $escapeTagsWhenHasAttributes = [];

    /**
     * @var array<string, array<string, bool>> 属性があればタグ unwrap に昇格 tag => attrSpec => true
     */
    private array $unwrapTagsWhenHasAttributes = [];

    /**
     * @var array<string, array<string, array<string, array<int, string>>>> 属性値条件で属性 drop tag => attr => op => [values]
     */
    private array $dropAttributesWhenValue = [];

    /**
     * @var array<string, array<string, array<string, array<int, string>>>> 属性値条件で属性 escape（隔離） tag => attr => op => [values]
     */
    private array $escapeAttributesWhenValue = [];

    /**
     * ファクトリです。
     *
     * @return self このインスタンス
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * コンストラクタです。
     */
    private function __construct()
    {
    }

    /**
     * タグ単位で drop します。
     *
     * @param  array<int, string> $tags タグ名配列（`*` 指定可）
     * @return self               このインスタンス
     */
    public function dropTags(array $tags): self
    {
        foreach ($tags as $tag) {
            $tag = $this->normalizeName((string) $tag);

            if ($tag === '') {
                continue;
            }

            $this->dropTags[$tag] = true;
        }

        return $this;
    }

    /**
     * タグ単位で escape します。
     *
     * @param  array<int, string> $tags タグ名配列（`*` 指定可）
     * @return self               このインスタンス
     */
    public function escapeTags(array $tags): self
    {
        foreach ($tags as $tag) {
            $tag = $this->normalizeName((string) $tag);

            if ($tag === '') {
                continue;
            }

            $this->escapeTags[$tag] = true;
        }

        return $this;
    }

    /**
     * タグ単位で unwrap します。
     *
     * @param  array<int, string> $tags タグ名配列（`*` 指定可）
     * @return self               このインスタンス
     */
    public function unwrapTags(array $tags): self
    {
        foreach ($tags as $tag) {
            $tag = $this->normalizeName((string) $tag);

            if ($tag === '') {
                continue;
            }

            $this->unwrapTags[$tag] = true;
        }

        return $this;
    }

    /**
     * 属性名（完全一致 + prefix）で属性を drop します。
     *
     * 仕様：
     * - `['*' => ['on*']]` のように `*` で任意タグを指定できます。
     * - 属性名末尾 `*` は prefix 指定です（例：`on*`）。
     *
     * @param  array<string, array<int, string>> $spec tag => [attrSpec...]
     * @return self                              このインスタンス
     */
    public function dropAttributesByName(array $spec): self
    {
        foreach ($spec as $tag => $attrSpecs) {
            $tag = $this->normalizeName((string) $tag);

            if ($tag === '') {
                continue;
            }

            foreach ((array) $attrSpecs as $attrSpec) {
                $attrSpec = $this->normalizeName((string) $attrSpec);

                if ($attrSpec === '') {
                    continue;
                }

                if (\str_ends_with($attrSpec, '*')) {
                    $prefix = \substr($attrSpec, 0, -1);

                    if ($prefix === '') {
                        continue;
                    }

                    $this->dropAttributesPrefix[$tag][$prefix] = true;
                    continue;
                }

                $this->dropAttributesExact[$tag][$attrSpec] = true;
            }
        }

        return $this;
    }

    /**
     * 指定属性を持つ場合にタグごと drop します。
     *
     * @param  array<string, array<int, string>> $spec tag => [attrSpec...]
     * @return self                              このインスタンス
     */
    public function dropTagsWhenHasAttributes(array $spec): self
    {
        $this->mergeAttributeSpecMap($this->dropTagsWhenHasAttributes, $spec);

        return $this;
    }

    /**
     * 指定属性を持つ場合にタグごと escape します。
     *
     * @param  array<string, array<int, string>> $spec tag => [attrSpec...]
     * @return self                              このインスタンス
     */
    public function escapeTagsWhenHasAttributes(array $spec): self
    {
        $this->mergeAttributeSpecMap($this->escapeTagsWhenHasAttributes, $spec);

        return $this;
    }

    /**
     * 指定属性を持つ場合にタグごと unwrap します。
     *
     * @param  array<string, array<int, string>> $spec tag => [attrSpec...]
     * @return self                              このインスタンス
     */
    public function unwrapTagsWhenHasAttributes(array $spec): self
    {
        $this->mergeAttributeSpecMap($this->unwrapTagsWhenHasAttributes, $spec);

        return $this;
    }

    /**
     * 属性値条件により属性を drop します。
     *
     * @param  array<string, array<string, array<string, array<int, string>>>> $spec tag => attr => op(lower_snake_case) => [values]
     * @return self                                                            このインスタンス
     */
    public function dropAttributesWhenValueMatches(array $spec): self
    {
        $this->mergeAttributeValueRules($this->dropAttributesWhenValue, $spec);

        return $this;
    }

    /**
     * 属性値条件により属性を escape（隔離）します。
     *
     * @param  array<string, array<string, array<string, array<int, string>>>> $spec tag => attr => op(lower_snake_case) => [values]
     * @return self                                                            このインスタンス
     */
    public function escapeAttributesWhenValueMatches(array $spec): self
    {
        $this->mergeAttributeValueRules($this->escapeAttributesWhenValue, $spec);

        return $this;
    }

    /**
     * 設定内容から HtmlSafetyRules を生成します。
     *
     * @return HtmlSafetyRules ルール値オブジェクト
     */
    public function build(): HtmlSafetyRules
    {
        return HtmlSafetyRules::fromCompiledMaps(
            $this->dropTags,
            $this->escapeTags,
            $this->unwrapTags,
            $this->dropAttributesExact,
            $this->dropAttributesPrefix,
            $this->dropTagsWhenHasAttributes,
            $this->escapeTagsWhenHasAttributes,
            $this->unwrapTagsWhenHasAttributes,
            $this->dropAttributesWhenValue,
            $this->escapeAttributesWhenValue,
        );
    }

    /**
     * タグ名・属性名を正規化します（トリム、小文字化、`*` の保持）。
     *
     * @param  string $name 対象名
     * @return string 正規化後
     */
    private function normalizeName(string $name): string
    {
        $name = \trim($name);

        if ($name === '*') {
            return '*';
        }

        return \strtolower($name);
    }

    /**
     * 属性 spec マップ（tag => attrSpec => true）へ追記します。
     *
     * @param array<string, array<string, bool>> $target 追記先
     * @param array<string, array<int, string>>  $spec   入力 spec
     */
    private function mergeAttributeSpecMap(array &$target, array $spec): void
    {
        foreach ($spec as $tag => $attrSpecs) {
            $tag = $this->normalizeName((string) $tag);

            if ($tag === '') {
                continue;
            }

            foreach ((array) $attrSpecs as $attrSpec) {
                $attrSpec = $this->normalizeName((string) $attrSpec);

                if ($attrSpec === '') {
                    continue;
                }

                $target[$tag][$attrSpec] = true;
            }
        }
    }

    /**
     * 属性値条件ルール（tag => attr => op => [values]）へ追記します。
     *
     * - 入力の op は lower_snake_case 文字列を想定します。
     * - enum を直接キーにしたい場合は、呼び出し側で `->value` を用いてください。
     *
     * @param array<string, array<string, array<string, array<int, string>>>> $target 追記先
     * @param array<string, array<string, array<string, array<int, string>>>> $spec   入力 spec
     */
    private function mergeAttributeValueRules(array &$target, array $spec): void
    {
        foreach ($spec as $tag => $attrs) {
            $tag = $this->normalizeName((string) $tag);

            if ($tag === '') {
                continue;
            }

            foreach ($attrs as $attrName => $ops) {
                $attrName = $this->normalizeName((string) $attrName);

                if ($attrName === '') {
                    continue;
                }

                foreach ($ops as $op => $values) {
                    $opKey = \is_string($op) ? $op : (string) $op;

                    if (HtmlAttributeValueMatchOperatorEnum::tryFromNormalized($opKey) === null) {
                        continue;
                    }

                    foreach ((array) $values as $value) {
                        $value = (string) $value;

                        if ($value === '') {
                            continue;
                        }

                        $target[$tag][$attrName][$opKey][] = $value;
                    }
                }
            }
        }
    }
}
