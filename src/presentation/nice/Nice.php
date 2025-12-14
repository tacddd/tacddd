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

use tacddd\presentation\nice\formatting\NiceFormatter;

/**
 * Nice 機能のシンプルなファサード。
 */
final class Nice
{
    /**
     * @var NiceFormatter Nice 変換の実体ロジック
     */
    private readonly NiceFormatter $formatter;

    /**
     * コンストラクタ。
     *
     * @param NiceFormatter $formatter Nice 変換の実体ロジック
     */
    public function __construct(NiceFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * 任意の値を人間可読な文字列へ変換します。
     *
     * @param  mixed            $value   変換対象
     * @param  null|NiceOptions $options フォーマット／ロカール／省略方針など
     * @return string           人間可読な文字列
     */
    public function of(mixed $value, ?NiceOptions $options = null): string
    {
        return $this->formatter->format($value, $options);
    }
}
