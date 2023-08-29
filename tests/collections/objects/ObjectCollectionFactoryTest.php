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

namespace tacddd\tests\collections\objects;

use PHPUnit\Framework\Attributes\Test;
use tacddd\collections\interfaces\UniqueIdFactoryInterface;
use tacddd\collections\objects\ObjectCollectionFactory;
use tacddd\tests\utilities\resources\dummy\objects\CollectionEntityDummy;
use tacddd\tests\utilities\test_cases\AbstractTestCase;

/**
 * @internal
 */
class ObjectCollectionFactoryTest extends AbstractTestCase
{
    #[Test]
    public function create(): void
    {
        $collection = ObjectCollectionFactory::create(
            CollectionEntityDummy::class,
            function(CollectionEntityDummy $element): string|int {
                return $element->getId();
            },
            [
                $asdf   = new CollectionEntityDummy(1, 'asdf', 'value1'),
                $zxcv   = new CollectionEntityDummy(2, 'zxcv', 'value2'),
                $qwer   = new CollectionEntityDummy(3, 'qwer', 'value3'),
            ],
        );

        $this->assertSame($asdf, $collection->first());
        $this->assertSame($qwer, $collection->last());

        $this->assertSame($zxcv, $collection->find(2));

        $this->assertSame([$zxcv], $collection->findById(2));
        $this->assertSame($zxcv, $collection->findOneById(2));

        $this->assertTrue($collection->hasById(2));
        $this->assertFalse($collection->hasById(4));

        $collection = ObjectCollectionFactory::create(
            CollectionEntityDummy::class,
            new class() implements UniqueIdFactoryInterface {
                /**
                 * 指定された値からユニークIDを返します。
                 *
                 * @param  mixed      $value 値
                 * @return int|string ユニークID
                 */
                public static function createUniqueId(mixed $value): string|int
                {
                    return $value->getId();
                }
            },
            [
                $asdf   = new CollectionEntityDummy(1, 'asdf', 'value1'),
                $zxcv   = new CollectionEntityDummy(2, 'zxcv', 'value2'),
                $qwer   = new CollectionEntityDummy(3, 'qwer', 'value3'),
            ],
        );

        $this->assertSame($asdf, $collection->first());
        $this->assertSame($qwer, $collection->last());

        $this->assertSame($zxcv, $collection->find(2));

        $this->assertSame([$zxcv], $collection->findById(2));

        $this->assertTrue($collection->hasById(2));
        $this->assertFalse($collection->hasById(4));
    }
}
