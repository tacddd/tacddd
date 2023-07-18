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

namespace tacddd\tests\utilities\test_cases\value_objects\traits;

use PHPUnit\Framework\Attributes\Test;
use tacddd\tests\utilities\resources\dummy\enums\BackedSuit;

/**
 * @internal
 */
trait ValueObjectConstructTypeErrorFromEnumTestCaseTrait
{
    #[Test]
    public function constructTypeErrorFromEnum(): void
    {
        $class_path = $this->getClassSpec()->getClassPath();

        $this->expectException(\TypeError::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessageMatches(\sprintf(
            '/must be of type %s, %s given/',
            \preg_quote($this->getClassSpec()->getPrimitiveType()),
            \preg_quote(BackedSuit::class),
        ));

        new $class_path(BackedSuit::Hearts);
    }
}
