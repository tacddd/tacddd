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

namespace tacddd\tests\cases\utilities\containers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use tacddd\tests\utilities\AbstractTestCase;
use tacddd\tests\utilities\resources\dummy\value_objects\traits\bools\BoolTraitDummay;
use tacddd\utilities\caching\interfaces\ValueObjectCacheServiceInterface;
use tacddd\utilities\containers\ContainerService;
use tacddd\utilities\converters\interfaces\StringServiceInterface;

/**
 * @internal
 */
#[CoversClass(ContainerService::class)]
class ContainerServiceTest extends AbstractTestCase
{
    #[Test]
    public function containerService(): void
    {
        $containerService   = ContainerService::create();

        $this->assertInstanceOf(ContainerService::class, $containerService);

        // ==============================================
        $actual = $containerService->set(BoolTraitDummay::class, BoolTraitDummay::class, true, [true]);
        $this->assertInstanceOf(ContainerService::class, $actual);

        $this->assertTrue($containerService->has(BoolTraitDummay::class));
        $this->assertTrue($containerService->has(StringServiceInterface::class));
        $this->assertFalse($containerService->has(''));

        $actual = $containerService->get(BoolTraitDummay::class);
        $this->assertInstanceOf(BoolTraitDummay::class, $actual);
        $this->assertSame($containerService->get(BoolTraitDummay::class), $actual);
    }

    #[Test]
    public function defaultService(): void
    {
        $containerService   = ContainerService::create();

        $this->assertInstanceOf(StringServiceInterface::class, $containerService->get(StringServiceInterface::class));
        $this->assertInstanceOf(StringServiceInterface::class, $containerService->getStringService());

        $this->assertInstanceOf(ValueObjectCacheServiceInterface::class, $containerService->get(ValueObjectCacheServiceInterface::class));
        $this->assertInstanceOf(ValueObjectCacheServiceInterface::class, $containerService->getValueObjectCacheService());
    }
}
