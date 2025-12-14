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

namespace tacddd\tests\cases\presentation\nice\contracts;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use tacddd\presentation\nice\contracts\Niceable;
use tacddd\presentation\nice\formatting\NiceFormatter;
use tacddd\presentation\nice\Nice;
use tacddd\tests\utilities\AbstractTestCase;

/**
 * @internal
 */
#[CoversClass(Niceable::class)]
class NiceableTest extends AbstractTestCase
{
    #[Test]
    public function niceable(): void
    {
        $niceable = new class() implements Niceable {
            public function nice(null|string|\UnitEnum $format = null): string
            {
                return 'nice';
            }
        };

        $this->assertSame('nice', $niceable->nice());

        // ==============================================
        $nice = new Nice(new NiceFormatter());

        $this->assertSame('nice', $nice->of('nice'));
        $this->assertSame('', $nice->of(''));
        $this->assertSame('0', $nice->of(0));
        $this->assertSame('1', $nice->of(1));
        $this->assertSame('0.0', $nice->of(0.0));
        $this->assertSame('0.1', $nice->of(0.1));
        $this->assertSame('1.0', $nice->of(1.0));
        $this->assertSame('1.1', $nice->of(1.1));
        $this->assertSame('NULL', $nice->of(null));
        $this->assertSame('true', $nice->of(true));
        $this->assertSame('false', $nice->of(false));
    }
}
