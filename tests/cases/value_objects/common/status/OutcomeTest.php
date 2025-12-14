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

namespace tacddd\tests\cases\value_objects\lang_types\php;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use tacddd\tests\utilities\AbstractTestCase;
use tacddd\utilities\status\value_objects\Outcome;

/**
 * @internal
 */
#[CoversClass(Outcome::class)]
class OutcomeTest extends AbstractTestCase
{
    /**
     * @test
     */
    #[Test]
    public function test(): void
    {
        $expected   = new Outcome(true);
        $this->assertEquals($expected, Outcome::of(true));
        $this->assertEquals($expected, Outcome::of('true'));
        $this->assertEquals($expected, Outcome::of('1'));
        $this->assertEquals($expected, Outcome::fromString('true'));
        $this->assertEquals($expected, Outcome::fromString('1'));

        $expected   = new Outcome(false);
        $this->assertEquals($expected, Outcome::of(false));
        $this->assertEquals($expected, Outcome::of('false'));
        $this->assertEquals($expected, Outcome::of('0'));
        $this->assertEquals($expected, Outcome::fromString('false'));
        $this->assertEquals($expected, Outcome::fromString('0'));
    }
}
