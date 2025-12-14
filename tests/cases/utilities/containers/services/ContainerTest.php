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

namespace tacddd\tests\cases\utilities\containers\services;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Container\NotFoundExceptionInterface;
use tacddd\tests\utilities\AbstractTestCase;
use tacddd\tests\utilities\resources\dummy\value_objects\bools\traits\BoolTraitDummay;
use tacddd\utilities\containers\services\Container;

/**
 * @internal
 */
#[CoversClass(Container::class)]
class ContainerTest extends AbstractTestCase
{
    #[Test]
    public function container(): void
    {
        $container  = new Container();

        // ==============================================
        $this->assertInstanceOf(Container::class, $container->set(BoolTraitDummay::class, BoolTraitDummay::class, true, false, [true]));

        $this->assertTrue($container->has(BoolTraitDummay::class));

        $actual = $container->get(BoolTraitDummay::class);
        $this->assertInstanceOf(BoolTraitDummay::class, $actual);

        $this->assertSame($container->get(BoolTraitDummay::class), $actual);

        $this->assertInstanceOf(Container::class, $container->set(BoolTraitDummay::class, BoolTraitDummay::class, false, false, [true]));

        $this->assertTrue($container->has(BoolTraitDummay::class));

        $actual = $container->get(BoolTraitDummay::class);
        $this->assertInstanceOf(BoolTraitDummay::class, $actual);

        $this->assertNotSame($container->get(BoolTraitDummay::class), $actual);

        // ==============================================
        $this->assertInstanceOf(Container::class, $container->set(BoolTraitDummay::class, function() {
            return new BoolTraitDummay(true);
        }, true));

        $this->assertTrue($container->has(BoolTraitDummay::class));

        $actual = $container->get(BoolTraitDummay::class);
        $this->assertInstanceOf(BoolTraitDummay::class, $actual);

        $this->assertSame($container->get(BoolTraitDummay::class), $actual);

        // ==============================================
        $this->assertInstanceOf(Container::class, $container->set(BoolTraitDummay::class, function() {
            return BoolTraitDummay::class;
        }, true, false, [true]));

        $this->assertTrue($container->has(BoolTraitDummay::class));

        $actual = $container->get(BoolTraitDummay::class);
        $this->assertInstanceOf(BoolTraitDummay::class, $actual);

        $this->assertSame($container->get(BoolTraitDummay::class), $actual);

        // ==============================================
        $this->assertInstanceOf(Container::class, $container->set(BoolTraitDummay::class, function($value) {
            return new BoolTraitDummay($value);
        }, true, false, [true]));

        $this->assertTrue($container->has(BoolTraitDummay::class));

        $actual = $container->get(BoolTraitDummay::class);
        $this->assertInstanceOf(BoolTraitDummay::class, $actual);

        $this->assertSame($container->get(BoolTraitDummay::class), $actual);
    }

    #[Test]
    public function notFound(): void
    {
        $container  = new Container();

        $this->expectException(NotFoundExceptionInterface::class);

        $container->get('hogehuga');
    }
}
