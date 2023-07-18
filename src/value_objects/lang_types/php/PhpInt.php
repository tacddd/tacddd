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

namespace tacddd\value_objects\lang_types\php;

use tacddd\value_objects\lang_types\php\abstracts\AbstractPhpInt;
use tacddd\value_objects\lang_types\php\traits\factory_methods\PhpIntFactoryMethodTrait;

/**
 * 言語型：PHP：int
 */
final readonly class PhpInt extends AbstractPhpInt
{
    use PhpIntFactoryMethodTrait;

    /**
     * ユビキタス言語名を返します。
     *
     * @return string ユビキタス言語名
     */
    public static function getName(): string
    {
        return 'PHP int 型';
    }
}
