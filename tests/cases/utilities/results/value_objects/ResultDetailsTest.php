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
<<<<<<< HEAD
 * @varsion     1.0.0
=======
 * @version     1.0.0
>>>>>>> master
 */

declare(strict_types=1);

namespace tacddd\tests\cases\utilities\results\value_objects;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use tacddd\tests\utilities\AbstractTestCase;
use tacddd\utilities\results\collections\ResultDetailsCollection;
use tacddd\utilities\results\value_objects\ResultDetails;

/**
 * @internal
 */
#[CoversClass(ResultDetails::class)]
class ResultDetailsTest extends AbstractTestCase
{
    #[Test]
    public function getDetailsCollection(): void
    {
        $resultDetails  = new ResultDetails('', null, null, null);

        $this->assertSame('', $resultDetails->getMessage());
<<<<<<< HEAD
        $this->assertSame(null, $resultDetails->getDetails());
        $this->assertSame(null, $resultDetails->getDetailsCollection());
        $this->assertSame(null, $resultDetails->getOutcome());
=======
        $this->assertNull($resultDetails->getDetails());
        $this->assertNull($resultDetails->getDetailsCollection());
        $this->assertNull($resultDetails->getOutcome());
>>>>>>> master

        $resultDetailsCollection = ResultDetailsCollection::of($resultDetails);
        $resultDetails           = new ResultDetails('', null, $resultDetailsCollection, null);

        $this->assertSame('', $resultDetails->getMessage());
<<<<<<< HEAD
        $this->assertSame(null, $resultDetails->getDetails());
        $this->assertSame($resultDetailsCollection, $resultDetails->getDetailsCollection());
        $this->assertSame(null, $resultDetails->getOutcome());
=======
        $this->assertNull($resultDetails->getDetails());
        $this->assertSame($resultDetailsCollection, $resultDetails->getDetailsCollection());
        $this->assertNull($resultDetails->getOutcome());
>>>>>>> master
    }
}
