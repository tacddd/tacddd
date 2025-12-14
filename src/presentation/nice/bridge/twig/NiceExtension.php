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

namespace tacddd\presentation\nice\bridge\twig;

use tacddd\presentation\nice\Nice;
use tacddd\presentation\nice\NiceOptions;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Twig 3.x 用の "nice" フィルタを提供する拡張。
 */
final class NiceExtension extends AbstractExtension
{
    /**
     * コンストラクタ。
     *
     * @param Nice $nice Nice 機能のファサード
     */
    public function __construct(
        private readonly Nice $nice,
    ) {
    }

    /**
     * 登録する Twig フィルタ一覧を返します。
     *
     * @return array<int, TwigFilter>
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('nice', [$this, 'nice']),
        ];
    }

    /**
     * Twig フィルタ実体。
     *
     * @param  mixed                 $value  変換対象
     * @param  null|string|\UnitEnum $format 任意のフォーマット名（Enum も可）
     * @return string                人間可読な文字列
     */
    public function nice(mixed $value, null|string|\UnitEnum $format = null): string
    {
        return $this->nice->of($value, $format !== null ? NiceOptions::format($format) : null);
    }
}
