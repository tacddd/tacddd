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

namespace tacddd\tests\cases\collections\objects;

use PHPUnit\Framework\Attributes\Test;
use tacddd\tests\utilities\AbstractTestCase;
use tacddd\collections\objects\ObjectCollectionFactory;
use tacddd\collections\interfaces\UniqueIdFactoryInterface;
use tacddd\tests\utilities\resources\dummy\objects\findValueBy\Id;
use tacddd\tests\utilities\resources\dummy\objects\findValueBy\Name;
use tacddd\tests\utilities\resources\dummy\objects\CollectionEntityDummy;
use tacddd\tests\utilities\resources\dummy\objects\findValueBy\FindValueByCollectionEntityDummy;
use tacddd\tests\utilities\resources\dummy\objects\findValueBy\FindValueByCollectionEntityDummyCollection;
use tacddd\tests\utilities\resources\dummy\objects\findValueBy\NameCollection;

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

        $this->assertEquals($collection->with([$zxcv]), $collection->findById(2));
        $this->assertSame([$zxcv], $collection->findByIdAsArray(2));
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

        $this->assertEquals($collection->with([$zxcv]), $collection->findById(2));
        $this->assertSame([$zxcv], $collection->findByIdAsArray(2));

        $this->assertTrue($collection->hasById(2));
        $this->assertFalse($collection->hasById(4));
    }

    #[Test]
    public function findValueBy(): void
    {
        $collection = new FindValueByCollectionEntityDummyCollection([
            new FindValueByCollectionEntityDummy(new Id(1), $name1 = new Name('asdf')),
            new FindValueByCollectionEntityDummy(new Id(2), new Name('zxcv')),
            new FindValueByCollectionEntityDummy(new Id(3), new Name('qwer')),
        ]);

        $this->assertEquals(
            new NameCollection([$name1]),
            $collection->findValueBy(['id' => 1], 'name', NameCollection::class)
        );
    }
}
