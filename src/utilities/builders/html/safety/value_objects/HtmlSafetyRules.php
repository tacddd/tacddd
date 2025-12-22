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

namespace tacddd\utilities\builders\html\safety\value_objects;

use tacddd\utilities\builders\html\safety\enums\HtmlAttributeValueMatchOperatorEnum;

/**
 * HTML の意図の安全性ルールです。
 *
 * 本クラスは「ルール定義（不変）」を保持します。
 * ルールの構築は HtmlSafetyRulesBuilder が担い、本クラスは評価に必要な参照 API を提供します。
 *
 * ワイルドカード仕様：
 * - タグ名：`*` は任意のタグ
 * - 属性名：末尾 `*` は prefix 指定（例：`on*`）
 *
 * 注意：
 * - 属性のリライト（例：href を別属性へ移す等）は行いません。
 * - DOM 変換（drop/escape/unwrap 等）は Html 側で行います。
 */
final class HtmlSafetyRules
{
    /**
     * @var array<string, bool> タグ drop ルール
     */
    private array $dropTags;

    /**
     * @var array<string, bool> タグ escape ルール
     */
    private array $escapeTags;

    /**
     * @var array<string, bool> タグ unwrap ルール
     */
    private array $unwrapTags;

    /**
     * @var array<string, array<string, bool>> 属性 drop（完全一致） tag => attr => true
     */
    private array $dropAttributesExact;

    /**
     * @var array<string, array<string, bool>> 属性 drop（prefix） tag => prefix => true
     */
    private array $dropAttributesPrefix;

    /**
     * @var array<string, array<string, bool>> 属性があればタグ drop に昇格 tag => attrSpec => true
     */
    private array $dropTagsWhenHasAttributes;

    /**
     * @var array<string, array<string, bool>> 属性があればタグ escape に昇格 tag => attrSpec => true
     */
    private array $escapeTagsWhenHasAttributes;

    /**
     * @var array<string, array<string, bool>> 属性があればタグ unwrap に昇格 tag => attrSpec => true
     */
    private array $unwrapTagsWhenHasAttributes;

    /**
     * @var array<string, array<string, array<string, array<int, string>>>> 属性値条件で属性 drop tag => attr => op => [values]
     */
    private array $dropAttributesWhenValue;

    /**
     * @var array<string, array<string, array<string, array<int, string>>>> 属性値条件で属性 escape（隔離） tag => attr => op => [values]
     */
    private array $escapeAttributesWhenValue;

    /**
     * コンパイル済みマップから生成します。
     *
     * @param  array<string, bool>                                             $drop_tags                       タグ drop
     * @param  array<string, bool>                                             $escape_tags                     タグ escape
     * @param  array<string, bool>                                             $unwrap_tags                     タグ unwrap
     * @param  array<string, array<string, bool>>                              $drop_attributes_exact           属性 drop（完全一致）
     * @param  array<string, array<string, bool>>                              $drop_attributes_prefix          属性 drop（prefix）
     * @param  array<string, array<string, bool>>                              $drop_tags_when_has_attributes   属性があればタグ drop
     * @param  array<string, array<string, bool>>                              $escape_tags_when_has_attributes 属性があればタグ escape
     * @param  array<string, array<string, bool>>                              $unwrap_tags_when_has_attributes 属性があればタグ unwrap
     * @param  array<string, array<string, array<string, array<int, string>>>> $drop_attributes_when_value      属性値条件で属性 drop
     * @param  array<string, array<string, array<string, array<int, string>>>> $escape_attributes_when_value    属性値条件で属性 escape（隔離）
     * @return self                                                            このインスタンス
     */
    public static function fromCompiledMaps(
        array $drop_tags,
        array $escape_tags,
        array $unwrap_tags,
        array $drop_attributes_exact,
        array $drop_attributes_prefix,
        array $drop_tags_when_has_attributes,
        array $escape_tags_when_has_attributes,
        array $unwrap_tags_when_has_attributes,
        array $drop_attributes_when_value,
        array $escape_attributes_when_value,
    ): self {
        return new self(
            $drop_tags,
            $escape_tags,
            $unwrap_tags,
            $drop_attributes_exact,
            $drop_attributes_prefix,
            $drop_tags_when_has_attributes,
            $escape_tags_when_has_attributes,
            $unwrap_tags_when_has_attributes,
            $drop_attributes_when_value,
            $escape_attributes_when_value,
        );
    }

    /**
     * コンストラクタです。
     *
     * @param array<string, bool>                                             $drop_tags                       タグ drop
     * @param array<string, bool>                                             $escape_tags                     タグ escape
     * @param array<string, bool>                                             $unwrap_tags                     タグ unwrap
     * @param array<string, array<string, bool>>                              $drop_attributes_exact           属性 drop（完全一致）
     * @param array<string, array<string, bool>>                              $drop_attributes_prefix          属性 drop（prefix）
     * @param array<string, array<string, bool>>                              $drop_tags_when_has_attributes   属性があればタグ drop
     * @param array<string, array<string, bool>>                              $escape_tags_when_has_attributes 属性があればタグ escape
     * @param array<string, array<string, bool>>                              $unwrap_tags_when_has_attributes 属性があればタグ unwrap
     * @param array<string, array<string, array<string, array<int, string>>>> $drop_attributes_when_value      属性値条件で属性 drop
     * @param array<string, array<string, array<string, array<int, string>>>> $escape_attributes_when_value    属性値条件で属性 escape（隔離）
     */
    private function __construct(
        array $drop_tags,
        array $escape_tags,
        array $unwrap_tags,
        array $drop_attributes_exact,
        array $drop_attributes_prefix,
        array $drop_tags_when_has_attributes,
        array $escape_tags_when_has_attributes,
        array $unwrap_tags_when_has_attributes,
        array $drop_attributes_when_value,
        array $escape_attributes_when_value,
    ) {
        $this->dropTags                    = $drop_tags;
        $this->escapeTags                  = $escape_tags;
        $this->unwrapTags                  = $unwrap_tags;
        $this->dropAttributesExact         = $drop_attributes_exact;
        $this->dropAttributesPrefix        = $drop_attributes_prefix;
        $this->dropTagsWhenHasAttributes   = $drop_tags_when_has_attributes;
        $this->escapeTagsWhenHasAttributes = $escape_tags_when_has_attributes;
        $this->unwrapTagsWhenHasAttributes = $unwrap_tags_when_has_attributes;
        $this->dropAttributesWhenValue     = $drop_attributes_when_value;
        $this->escapeAttributesWhenValue   = $escape_attributes_when_value;
    }

    /**
     * タグが drop 対象か判定します。
     *
     * @param  string $tag_name タグ名
     * @return bool   drop 対象の場合 true
     */
    public function shouldDropTag(string $tag_name): bool
    {
        $tag_name = $this->normalizeName($tag_name);

        return isset($this->dropTags[$tag_name]) || isset($this->dropTags['*']);
    }

    /**
     * タグが escape 対象か判定します。
     *
     * @param  string $tag_name タグ名
     * @return bool   escape 対象の場合 true
     */
    public function shouldEscapeTag(string $tag_name): bool
    {
        $tag_name = $this->normalizeName($tag_name);

        return isset($this->escapeTags[$tag_name]) || isset($this->escapeTags['*']);
    }

    /**
     * タグが unwrap 対象か判定します。
     *
     * @param  string $tag_name タグ名
     * @return bool   unwrap 対象の場合 true
     */
    public function shouldUnwrapTag(string $tag_name): bool
    {
        $tag_name = $this->normalizeName($tag_name);

        return isset($this->unwrapTags[$tag_name]) || isset($this->unwrapTags['*']);
    }

    /**
     * 指定タグで、指定属性が「属性 drop 対象」か判定します（完全一致 + prefix）。
     *
     * @param  string $tag_name       タグ名
     * @param  string $attribute_name 属性名
     * @return bool   drop 対象の場合 true
     */
    public function shouldDropAttribute(string $tag_name, string $attribute_name): bool
    {
        $tag_name       = $this->normalizeName($tag_name);
        $attribute_name = $this->normalizeName($attribute_name);

        if ($this->matchAttributeExact($tag_name, $attribute_name, $this->dropAttributesExact)) {
            return true;
        }

        return $this->matchAttributePrefix($tag_name, $attribute_name, $this->dropAttributesPrefix);
    }

    /**
     * 指定タグで、指定属性が存在する場合「タグ drop」に昇格するか判定します。
     *
     * @param  string $tag_name       タグ名
     * @param  string $attribute_name 属性名
     * @return bool   昇格する場合 true
     */
    public function shouldDropTagWhenHasAttribute(string $tag_name, string $attribute_name): bool
    {
        return $this->matchAttributeSpec($tag_name, $attribute_name, $this->dropTagsWhenHasAttributes);
    }

    /**
     * 指定タグで、指定属性が存在する場合「タグ escape」に昇格するか判定します。
     *
     * @param  string $tag_name       タグ名
     * @param  string $attribute_name 属性名
     * @return bool   昇格する場合 true
     */
    public function shouldEscapeTagWhenHasAttribute(string $tag_name, string $attribute_name): bool
    {
        return $this->matchAttributeSpec($tag_name, $attribute_name, $this->escapeTagsWhenHasAttributes);
    }

    /**
     * 指定タグで、指定属性が存在する場合「タグ unwrap」に昇格するか判定します。
     *
     * @param  string $tag_name       タグ名
     * @param  string $attribute_name 属性名
     * @return bool   昇格する場合 true
     */
    public function shouldUnwrapTagWhenHasAttribute(string $tag_name, string $attribute_name): bool
    {
        return $this->matchAttributeSpec($tag_name, $attribute_name, $this->unwrapTagsWhenHasAttributes);
    }

    /**
     * 属性値条件により、属性を drop するか判定します。
     *
     * @param  string $tag_name       タグ名
     * @param  string $attribute_name 属性名
     * @param  string $value          属性値
     * @return bool   条件一致で drop する場合 true
     */
    public function shouldDropAttributeWhenValue(string $tag_name, string $attribute_name, string $value): bool
    {
        return $this->matchAttributeValueRules($tag_name, $attribute_name, $value, $this->dropAttributesWhenValue);
    }

    /**
     * 属性値条件により、属性を escape（隔離）するか判定します。
     *
     * @param  string $tag_name       タグ名
     * @param  string $attribute_name 属性名
     * @param  string $value          属性値
     * @return bool   条件一致で escape（隔離）する場合 true
     */
    public function shouldEscapeAttributeWhenValue(string $tag_name, string $attribute_name, string $value): bool
    {
        return $this->matchAttributeValueRules($tag_name, $attribute_name, $value, $this->escapeAttributesWhenValue);
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
     * 属性 spec（完全一致 + suffix wildcard）に一致するか判定します。
     *
     * @param  string                             $tag_name       タグ名（未正規化可）
     * @param  string                             $attribute_name 属性名（未正規化可）
     * @param  array<string, array<string, bool>> $map            tag => attrSpec => true
     * @return bool                               一致する場合 true
     */
    private function matchAttributeSpec(string $tag_name, string $attribute_name, array $map): bool
    {
        $tag_name       = $this->normalizeName($tag_name);
        $attribute_name = $this->normalizeName($attribute_name);

        if ($this->matchAttributeSpecInTag($tag_name, $attribute_name, $map)) {
            return true;
        }

        return $this->matchAttributeSpecInTag('*', $attribute_name, $map);
    }

    /**
     * 指定タグの範囲で、属性 spec に一致するか判定します。
     *
     * @param  string                             $tag_name       タグ名（正規化済み）
     * @param  string                             $attribute_name 属性名（正規化済み）
     * @param  array<string, array<string, bool>> $map            tag => attrSpec => true
     * @return bool                               一致する場合 true
     */
    private function matchAttributeSpecInTag(string $tag_name, string $attribute_name, array $map): bool
    {
        if (!isset($map[$tag_name])) {
            return false;
        }

        $specs = $map[$tag_name];

        if (isset($specs[$attribute_name])) {
            return true;
        }

        foreach ($specs as $spec => $true) {
            if ($spec === '*') {
                return true;
            }

            if (\str_ends_with($spec, '*')) {
                $prefix = \substr($spec, 0, -1);

                if ($prefix !== '' && \str_starts_with($attribute_name, $prefix)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * 属性 drop（完全一致）に一致するか判定します。
     *
     * @param  string                             $tag_name       タグ名（正規化済み）
     * @param  string                             $attribute_name 属性名（正規化済み）
     * @param  array<string, array<string, bool>> $map            tag => attr => true
     * @return bool                               一致する場合 true
     */
    private function matchAttributeExact(string $tag_name, string $attribute_name, array $map): bool
    {
        if (isset($map[$tag_name][$attribute_name])) {
            return true;
        }

        return isset($map['*'][$attribute_name]);
    }

    /**
     * 属性 drop（prefix）に一致するか判定します。
     *
     * @param  string                             $tag_name       タグ名（正規化済み）
     * @param  string                             $attribute_name 属性名（正規化済み）
     * @param  array<string, array<string, bool>> $map            tag => prefix => true
     * @return bool                               一致する場合 true
     */
    private function matchAttributePrefix(string $tag_name, string $attribute_name, array $map): bool
    {
        if ($this->matchAttributePrefixInTag($tag_name, $attribute_name, $map)) {
            return true;
        }

        return $this->matchAttributePrefixInTag('*', $attribute_name, $map);
    }

    /**
     * 指定タグの範囲で、属性 prefix に一致するか判定します。
     *
     * @param  string                             $tag_name       タグ名（正規化済み）
     * @param  string                             $attribute_name 属性名（正規化済み）
     * @param  array<string, array<string, bool>> $map            tag => prefix => true
     * @return bool                               一致する場合 true
     */
    private function matchAttributePrefixInTag(string $tag_name, string $attribute_name, array $map): bool
    {
        if (!isset($map[$tag_name])) {
            return false;
        }

        foreach ($map[$tag_name] as $prefix => $true) {
            if ($prefix === '') {
                continue;
            }

            if (\str_starts_with($attribute_name, $prefix)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 属性値条件ルールに一致するか判定します。
     *
     * @param  string                                                          $tag_name       タグ名
     * @param  string                                                          $attribute_name 属性名
     * @param  string                                                          $value          属性値
     * @param  array<string, array<string, array<string, array<int, string>>>> $rules          tag => attr => op => [values]
     * @return bool                                                            一致する場合 true
     */
    private function matchAttributeValueRules(string $tag_name, string $attribute_name, string $value, array $rules): bool
    {
        $tag_name       = $this->normalizeName($tag_name);
        $attribute_name = $this->normalizeName($attribute_name);

        if ($this->matchAttributeValueRulesInTag($tag_name, $attribute_name, $value, $rules)) {
            return true;
        }

        return $this->matchAttributeValueRulesInTag('*', $attribute_name, $value, $rules);
    }

    /**
     * 指定タグの範囲で、属性値条件ルールに一致するか判定します。
     *
     * @param  string                                                          $tag_name       タグ名（正規化済み）
     * @param  string                                                          $attribute_name 属性名（正規化済み）
     * @param  string                                                          $value          属性値
     * @param  array<string, array<string, array<string, array<int, string>>>> $rules          tag => attr => op => [values]
     * @return bool                                                            一致する場合 true
     */
    private function matchAttributeValueRulesInTag(string $tag_name, string $attribute_name, string $value, array $rules): bool
    {
        if (!isset($rules[$tag_name][$attribute_name])) {
            return false;
        }

        foreach ($rules[$tag_name][$attribute_name] as $op => $values) {
            foreach ($values as $needle) {
                if ($this->matchValue((string) $op, (string) $needle, $value)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * 演算子に基づき属性値を判定します。
     *
     * @param  string $op     演算子（lower_snake_case）
     * @param  string $needle 比較対象（needle）
     * @param  string $value  属性値
     * @return bool   一致する場合 true
     */
    private function matchValue(string $op, string $needle, string $value): bool
    {
        $operator = HtmlAttributeValueMatchOperatorEnum::tryFromNormalized($op);

        if ($operator === null) {
            return false;
        }

        return match ($operator) {
            HtmlAttributeValueMatchOperatorEnum::Prefix   => $needle !== '' && \str_starts_with($value, $needle),
            HtmlAttributeValueMatchOperatorEnum::Equals   => $value === $needle,
            HtmlAttributeValueMatchOperatorEnum::Contains => $needle !== '' && \str_contains($value, $needle),
        };
    }
}
