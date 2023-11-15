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

namespace tacddd\tests\cases\utilities\containers\value_objects;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use tacddd\tests\utilities\AbstractTestCase;
use tacddd\tests\utilities\resources\dummy\value_objects\bools\traits\BoolTraitDummay;
use tacddd\utilities\containers\ContainerService;
use tacddd\utilities\containers\services\Container;
use tacddd\utilities\containers\value_objects\ContainerAccessor;
use tacddd\utilities\containers\value_objects\ContainerAdapter;
use tacddd\utilities\containers\value_objects\traits\adapters\ContainerAdapterInterface;
use tacddd\utilities\converters\interfaces\StringServiceInterface;

/**
 * @internal
 */
#[CoversClass(ContainerAdapter::class)]
final class ContainerAdapterTest extends AbstractTestCase
{
    #[Test]
    public function containerAdapter(): void
    {
        $containerAdapter   = new ContainerAdapter(
            new Container(),
            new ContainerAccessor(),
            ContainerService::getDefaultSettings(),
        );

        // ==============================================
        $actual = $containerAdapter->set(BoolTraitDummay::class, BoolTraitDummay::class, true, false, [true]);
        $this->assertInstanceOf(ContainerAdapterInterface::class, $actual);
        $this->assertInstanceOf(ContainerAdapter::class, $actual);

        $this->assertTrue($containerAdapter->has(BoolTraitDummay::class));
        $this->assertTrue($containerAdapter->has(StringServiceInterface::class));
        $this->assertFalse($containerAdapter->has(''));

        $actual = $containerAdapter->get(BoolTraitDummay::class);
        $this->assertInstanceOf(BoolTraitDummay::class, $actual);
        $this->assertSame($containerAdapter->get(BoolTraitDummay::class), $actual);

        // ==============================================
        $actual = $containerAdapter->set(BoolTraitDummay::class, function(): BoolTraitDummay {
            return new BoolTraitDummay(true);
        }, true);
        $this->assertInstanceOf(ContainerAdapterInterface::class, $actual);
        $this->assertInstanceOf(ContainerAdapter::class, $actual);

        $this->assertTrue($containerAdapter->has(BoolTraitDummay::class));
        $this->assertTrue($containerAdapter->has(StringServiceInterface::class));
        $this->assertFalse($containerAdapter->has(''));

        $actual = $containerAdapter->get(BoolTraitDummay::class);
        $this->assertInstanceOf(BoolTraitDummay::class, $actual);
        $this->assertSame($containerAdapter->get(BoolTraitDummay::class), $actual);

        // ==============================================
        $actual = $containerAdapter->set(BoolTraitDummay::class, function(): string {
            return BoolTraitDummay::class;
        }, true, false, [true]);
        $this->assertInstanceOf(ContainerAdapterInterface::class, $actual);
        $this->assertInstanceOf(ContainerAdapter::class, $actual);

        $this->assertTrue($containerAdapter->has(BoolTraitDummay::class));
        $this->assertTrue($containerAdapter->has(StringServiceInterface::class));
        $this->assertFalse($containerAdapter->has(''));

        $actual = $containerAdapter->get(BoolTraitDummay::class);
        $this->assertInstanceOf(BoolTraitDummay::class, $actual);
        $this->assertSame($containerAdapter->get(BoolTraitDummay::class), $actual);
    }
}
