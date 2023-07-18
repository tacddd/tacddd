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

namespace tacddd\value_objects\lang_types\php\abstracts;

use tacddd\value_objects\interfaces\ValueObjectInterface;

/**
 * 抽象言語型：PHP：trait
 */
abstract readonly class AbstractPhpTrait implements ValueObjectInterface
{
    /**
     * constructor
     *
     * @param string $value    値
     * @param bool   $autoload 値の検証時にオートロードするかどうか
     */
    public function __construct(public string $value, bool $autoload = true)
    {
        if (!\trait_exists($value, $autoload)) {
            throw new \ValueError(\sprintf('存在しないまたはアクセスできないトレイト（"%s"）を指定されました。', $value));
        }
    }
}
