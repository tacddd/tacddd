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

namespace tacd\tests\collections\traits\objects;

use PHPUnit\Framework\Attributes\Test;
use tacd\tests\utilities\resources\dummy\objects\CollectionDummy;
use tacd\tests\utilities\resources\dummy\objects\CollectionElementDummy;
use tacd\tests\utilities\test_cases\AbstractTestCase;

/**
 * @internal
 */
class MagicalAccessableObjectCollectionTest extends AbstractTestCase
{
    #[Test]
    public function collection(): void
    {
        $collection     = new CollectionDummy([
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

        $hjkl           = new CollectionElementDummy(2, 'hjkl', 'value4');

        $collection->setByIdInGroup($hjkl);

        $this->assertTrue($collection->hasById(2));
        $this->assertTrue($collection->hasByIdInGroup([2, 'hjkl']));
        $this->assertFalse($collection->hasByIdInGroup([2, 'zxcv']));

        $this->assertSame($hjkl, $collection->getById(2));
        $this->assertSame($hjkl, $collection->getByIdInGroup([2, 'hjkl']));

        $yuio           = new CollectionElementDummy(2, 'yuio', 'value5');
        $collection->setByIdInGroupInName($yuio);

        $this->assertTrue($collection->hasById(2));
        $this->assertTrue($collection->hasByIdInGroup([2, 'yuio']));
        $this->assertFalse($collection->hasByIdInGroup([2, 'hjkl']));
        $this->assertFalse($collection->hasByIdInGroup([2, 'zxcv']));

        $this->assertSame($yuio, $collection->getById(2));
        $this->assertSame($yuio, $collection->getByIdInGroup([2, 'yuio']));
        $this->assertSame($yuio, $collection->getByIdInGroupInName([2, 'yuio', 'value5']));

        $collection->remove(2);
        $this->assertFalse($collection->has(2));
        $this->assertFalse($collection->hasById(2));
        $this->assertFalse($collection->hasByIdInGroup([2, 'yuio']));
        $this->assertFalse($collection->hasByIdInGroup([2, 'hjkl']));
        $this->assertFalse($collection->hasByIdInGroup([2, 'zxcv']));

        $collection->setByIdInGroup($yuio);
        $this->assertTrue($collection->has(2));
        $this->assertTrue($collection->hasByIdInGroup([2, 'yuio']));

        $collection->removeByIdInGroup([2, 'yuio']);
        $this->assertFalse($collection->has(2));
        $this->assertFalse($collection->hasByIdInGroup([2, 'yuio']));

        $collection     = new CollectionDummy([
            $asdf   = new CollectionElementDummy(1, 'asdf', 'value1'),
            $zxcv   = new CollectionElementDummy(2, 'asdf', 'value2'),
            $qwer   = new CollectionElementDummy(3, 'zxcv', 'value3'),
        ]);
    }

    #[Test]
    public function typeError(): void
    {
        $asdf           = new CollectionElementDummy(1, 'asdf', 'value1');
        $zxcv           = new CollectionElementDummy(2, 'zxcv', 'value2');

        $collection     = new CollectionDummy([]);

        $collection->setById($asdf, $zxcv);
        $this->assertSame($asdf, $collection->get(1));
        $this->assertSame($zxcv, $collection->get(2));

        $this->assertSame($asdf, $collection->getByIdInGroup([1, 'asdf']));
        $this->assertSame($asdf, $collection->getByIdInGroup(1, 'asdf'));

        $collection->removeByIdInGroup([1, 'asdf']);
        $this->assertFalse($collection->hasByIdInGroup([1, 'asdf']));

        $collection->setById($asdf);
        $collection->removeByIdInGroup(1, 'asdf');
        $this->assertFalse($collection->hasByIdInGroup(1, 'asdf'));
    }
}
