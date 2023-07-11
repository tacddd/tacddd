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

namespace tacddd\tests\collections;

use PHPUnit\Framework\Attributes\Test;
use tacddd\collections\interfaces\objects\UniqueKeyFactoryInterface;
use tacddd\collections\MagicalAccessCollectionFactory;
use tacddd\tests\utilities\resources\dummy\objects\CollectionElementDummy;
use tacddd\tests\utilities\test_cases\AbstractTestCase;

/**
 * @internal
 */
class MagicalAccessCollectionFactoryTest extends AbstractTestCase
{
    #[Test]
    public function createObjectCollection(): void
    {
        $collection = MagicalAccessCollectionFactory::createObjectCollection(CollectionElementDummy::class, function(CollectionElementDummy $element): string|int {
            return $element->getId();
        }, [
            $asdf   = new CollectionElementDummy(1, 'asdf', 'value1'),
            $zxcv   = new CollectionElementDummy(2, 'zxcv', 'value2'),
            $qwer   = new CollectionElementDummy(3, 'qwer', 'value3'),
        ]);

        $this->assertSame($asdf, $collection->first());
        $this->assertSame($qwer, $collection->last());

        $this->assertSame($zxcv, $collection->get(2));

        $this->assertSame($zxcv, $collection->getById(2));
        $this->assertSame($zxcv, $collection->getByIdInGroup([2, 'zxcv']));

        $this->assertTrue($collection->hasById(2));
        $this->assertFalse($collection->hasById(4));

        $this->assertTrue($collection->hasByIdInGroup([2, 'zxcv']));
        $this->assertFalse($collection->hasByIdInGroup([4, 'zxcv']));
        $this->assertFalse($collection->hasByIdInGroup([1, 'zxcv']));
        $this->assertFalse($collection->hasByIdInGroup([4, 'tyui']));

        $collection = MagicalAccessCollectionFactory::createObjectCollection(
            CollectionElementDummy::class,
            new class() implements UniqueKeyFactoryInterface {
                /**
                 * 指定されたオブジェクトからユニークキーを返します。
                 *
                 * @param  CollectionElementDummy $element オブジェクト
                 * @return int|string             ユニークキー
                 */
                public static function createUniqueKey(object $element): string|int
                {
                    return $element->getId();
                }
            },
            [
                $asdf   = new CollectionElementDummy(1, 'asdf', 'value1'),
                $zxcv   = new CollectionElementDummy(2, 'zxcv', 'value2'),
                $qwer   = new CollectionElementDummy(3, 'qwer', 'value3'),
            ],
        );

        $this->assertSame($asdf, $collection->first());
        $this->assertSame($qwer, $collection->last());

        $this->assertSame($zxcv, $collection->get(2));

        $this->assertSame($zxcv, $collection->getById(2));
        $this->assertSame($zxcv, $collection->getByIdInGroup([2, 'zxcv']));

        $this->assertTrue($collection->hasById(2));
        $this->assertFalse($collection->hasById(4));

        $this->assertTrue($collection->hasByIdInGroup([2, 'zxcv']));
        $this->assertFalse($collection->hasByIdInGroup([4, 'zxcv']));
        $this->assertFalse($collection->hasByIdInGroup([1, 'zxcv']));
        $this->assertFalse($collection->hasByIdInGroup([4, 'tyui']));
    }
}
