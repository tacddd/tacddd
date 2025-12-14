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

namespace tacddd\presentation\nice;

use tacddd\presentation\nice\locale\LocaleContext;
use tacddd\presentation\nice\policy\TruncationPolicy;

/**
 * Nice 出力に関するオプションを束ねる値オブジェクト。
 */
final class NiceOptions
{
    /**
     * @var null|string|\UnitEnum 出力フォーマット（例: 'Y/m' または DateFormatEnum::Y_M）
     */
    public readonly null|string|\UnitEnum $format;

    /**
     * @var null|LocaleContext ロカール指定（例: 'ja_JP' または LocaleIdEnum::JA_JP）
     */
    public readonly ?LocaleContext $locale;

    /**
     * @var null|TruncationPolicy 省略方針（最大幅・単位・省略記号）
     */
    public readonly ?TruncationPolicy $truncation;

    /**
     * フォーマットのみを素早く指定したい場合のヘルパ。
     *
     * @param string|\UnitEnum $format 出力フォーマット
     */
    public static function format(string|\UnitEnum $format): self
    {
        return new self(format: $format);
    }

    /**
     * コンストラクタ。
     *
     * @param null|string|\UnitEnum $format     出力フォーマット
     * @param null|LocaleContext    $locale     ロカール指定
     * @param null|TruncationPolicy $truncation 省略方針
     */
    public function __construct(
        null|string|\UnitEnum $format = null,
        ?LocaleContext $locale = null,
        ?TruncationPolicy $truncation = null,
    ) {
        $this->format     = $format;
        $this->locale     = $locale;
        $this->truncation = $truncation;
    }
}
