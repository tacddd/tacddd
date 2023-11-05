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

namespace tacddd\tests\cases\utilities\caching;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use tacddd\tests\utilities\AbstractTestCase;
use tacddd\tests\utilities\resources\dummy\value_objects\traits\bools\BoolTraitDummay;
use tacddd\utilities\caching\ValueObjectCacheService;

/**
 * @internal
 */
#[CoversClass(ValueObjectCacheService::class)]
class ValueObjectCacheServiceTest extends AbstractTestCase
{
    #[Test]
    public function containerService(): void
    {
        $valueObjectCacheService = new ValueObjectCacheService();

        $actual = $valueObjectCacheService->cacheableFromString(BoolTraitDummay::class, 'true');

        $this->assertSame($valueObjectCacheService->cacheableFromString(BoolTraitDummay::class, 'true'), $actual);

        $actual = $valueObjectCacheService->cacheableFromString(BoolTraitDummay::class, 'false');

        $this->assertSame($valueObjectCacheService->cacheableFromString(BoolTraitDummay::class, 'false'), $actual);
    }
}
