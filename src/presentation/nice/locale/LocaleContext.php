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

namespace tacddd\presentation\nice\locale;

use tacddd\presentation\nice\formatting\FormatNormalizer;

/**
 * ロカール情報（言語・地域設定）を扱うためのコンテキスト。
 */
final class LocaleContext
{
    /**
     * @var null|string|\UnitEnum ロカール指定
     */
    public readonly null|string|\UnitEnum $locale;

    /**
     * コンストラクタ。
     *
     * @param null|string|\UnitEnum $locale ロカール指定
     */
    public function __construct(null|string|\UnitEnum $locale = null)
    {
        $this->locale = $locale;
    }

    /**
     * 正規化済みロカール文字列（BackedEnum は value、UnitEnum は name）を返します。
     *
     * @return null|string 正規化後のロカール
     */
    public function normalized(): ?string
    {
        return FormatNormalizer::toString($this->locale);
    }
}
