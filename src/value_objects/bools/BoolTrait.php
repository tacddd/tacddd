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

namespace tacddd\value_objects\bools;

use tacddd\value_objects\bools\traits\BoolFactoryMethodTrait;
use tacddd\value_objects\bools\traits\BoolFromStringTrait;
use tacddd\value_objects\bools\traits\BoolNormalizationTrait;
use tacddd\value_objects\bools\traits\BoolVerificationFromStringTrait;
use tacddd\value_objects\bools\traits\BoolVerificationTrait;

/**
 * 真偽値特性
 */
trait BoolTrait
{
    use BoolFactoryMethodTrait;
    use BoolFromStringTrait;
    use BoolNormalizationTrait;
    use BoolVerificationTrait;
    use BoolVerificationFromStringTrait;
}
