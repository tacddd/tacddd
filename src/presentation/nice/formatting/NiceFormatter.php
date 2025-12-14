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

namespace tacddd\presentation\nice\formatting;

use tacddd\presentation\nice\contracts\Niceable;
use tacddd\presentation\nice\NiceOptions;
use tacddd\utilities\converters\StringService;

/**
 * Niceable 実装または一般的な値を、人間可読な文字列へ変換するフォーマッタ。
 */
final class NiceFormatter
{
    /**
     * 値を人間可読な文字列へ変換します。
     *
     * @param  mixed            $value   変換対象
     * @param  null|NiceOptions $options 追加オプション（format／ロカール／省略方針 等）
     * @return string           人間可読な文字列
     */
    public function format(mixed $value, ?NiceOptions $options = null): string
    {
        static $stringService = new StringService();

        $options ??= new NiceOptions();

        $text = match (true) {
            $value instanceof Niceable      => $value->nice($options->format),
            $value instanceof \Stringable   => (string) $value,
            \is_string($value)              => $value,
            default                         => $stringService->toDebugString($value),
        };

        if ($options->truncation !== null) {
            $text = $options->truncation->apply($text);
        }

        return $text;
    }
}
