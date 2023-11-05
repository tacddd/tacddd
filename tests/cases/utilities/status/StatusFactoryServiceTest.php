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

namespace tacddd\tests\cases\utilities\status;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use tacddd\tests\utilities\AbstractTestCase;
use tacddd\utilities\status\StatusFactoryService;

/**
 * @internal
 */
#[CoversClass(StatusFactoryService::class)]
class StatusFactoryServiceTest extends AbstractTestCase
{
    #[Test]
    public function outcome(): void
    {
        $this->assertSame(StatusFactoryService::createSuccess(), StatusFactoryService::createOutcome(true));
        $this->assertSame(StatusFactoryService::createFailure(), StatusFactoryService::createOutcome(false));
        $this->assertSame(StatusFactoryService::createSuccess(), StatusFactoryService::createSuccess());
        $this->assertSame(StatusFactoryService::createFailure(), StatusFactoryService::createFailure());
    }
}
