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

namespace tacddd\tests\collections\entities\traits;

use PHPUnit\Framework\Attributes\Test;
use tacddd\tests\utilities\resources\dummy\objects\CollectionDummy;
use tacddd\tests\utilities\resources\dummy\objects\CollectionElementDummy;
use tacddd\tests\utilities\test_cases\AbstractTestCase;

/**
 * @internal
 */
class EntityCollectionTest extends AbstractTestCase
{
    #[Test]
    public function collection(): void
    {
        $collection     = new CollectionDummy([
            $asdf   = new CollectionElementDummy(1, 'asdf', 'value1'),
            $zxcv   = new CollectionElementDummy(2, 'zxcv', 'value2'),
            $qwer   = new CollectionElementDummy(3, 'qwer', 'value3'),
        ]);

        // init
        $this->assertFalse($collection->empty());
        $this->assertSame(3, $collection->count());

        $this->assertSame($asdf, $collection->first());
        $this->assertSame($qwer, $collection->last());

        $this->assertSame($zxcv, $collection->find(2));
        $this->assertSame([$zxcv], $collection->findBy(['id' => 2]));
        $this->assertSame($zxcv, $collection->findOneBy(['id' => 2]));
        $this->assertSame(['zxcv' => [2 => [$zxcv]]], $collection->findToMapBy(['id' => 2], ['group', 'id']));
        $this->assertSame(['zxcv' => [2 => $zxcv]], $collection->findOneToMapBy(['id' => 2], ['group', 'id']));

        // add
        $collection->add($hjkl = new CollectionElementDummy(4, 'qwer', 'value4'));

        $this->assertFalse($collection->empty());
        $this->assertSame(4, $collection->count());

        $this->assertSame($asdf, $collection->first());
        $this->assertSame($hjkl, $collection->last());

        $this->assertSame($zxcv, $collection->find(2));
        $this->assertSame([$zxcv], $collection->findBy(['id' => 2]));
        $this->assertSame($zxcv, $collection->findOneBy(['id' => 2]));
        $this->assertSame(['zxcv' => [2 => [$zxcv]]], $collection->findToMapBy(['id' => 2], ['group', 'id']));
        $this->assertSame(['zxcv' => [2 => $zxcv]], $collection->findOneToMapBy(['id' => 2], ['group', 'id']));

        $this->assertSame(['qwer' => [3 => [$qwer], 4 => [$hjkl]]], $collection->findToMapBy(['group' => 'qwer'], ['group', 'id']));
        $this->assertSame(['qwer' => [3 => $qwer, 4 => $hjkl]], $collection->findOneToMapBy(['group' => 'qwer'], ['group', 'id']));

        $this->assertSame(['qwer' => [$qwer, $hjkl]], $collection->findToMapBy(['group' => 'qwer']));
        $this->assertSame(['qwer' => $qwer], $collection->findOneToMapBy(['group' => 'qwer']));

        // set
        $collection->add($nm = new CollectionElementDummy(2, 'nm', 'value2_2'));

        $this->assertFalse($collection->empty());
        $this->assertSame(4, $collection->count());

        $this->assertSame($asdf, $collection->first());
        $this->assertSame($hjkl, $collection->last());

        $this->assertSame($nm, $collection->find(2));

        $this->assertSame([$nm], $collection->findBy(['id' => 2]));
        $this->assertSame($nm, $collection->findOneBy(['id' => 2]));

        $this->assertSame(['nm' => [2 => [$nm]]], $collection->findToMapBy(['id' => 2], ['group', 'id']));
        $this->assertSame(['nm' => [2 => $nm]], $collection->findOneToMapBy(['id' => 2], ['group', 'id']));

        $this->assertSame(['qwer' => [3 => [$qwer], 4 => [$hjkl]]], $collection->findToMapBy(['group' => 'qwer'], ['group', 'id']));
        $this->assertSame(['qwer' => [3 => $qwer, 4 => $hjkl]], $collection->findOneToMapBy(['group' => 'qwer'], ['group', 'id']));

        // remove
        $collection->remove($zxcv); // unique idがマッチするオブジェクトの削除のため、zxcvでもnmでも同じ効用となる

        $this->assertFalse($collection->empty());
        $this->assertSame(3, $collection->count());

        $this->assertSame($asdf, $collection->first());
        $this->assertSame($hjkl, $collection->last());

        $this->assertSame(null, $collection->find(2));

        $this->assertSame([], $collection->findBy(['id' => 2]));
        $this->assertSame(null, $collection->findOneBy(['id' => 2]));
        $this->assertSame([], $collection->findToMapBy(['id' => 2], ['group', 'id']));

        $this->assertSame(['qwer' => [3 => [$qwer], 4 => [$hjkl]]], $collection->findToMapBy(['group' => 'qwer'], ['group', 'id']));
        $this->assertSame(['qwer' => [3 => $qwer, 4 => $hjkl]], $collection->findOneToMapBy(['group' => 'qwer'], ['group', 'id']));
    }

    #[Test]
    public function toMap(): void
    {
        $collection     = new CollectionDummy([
            $asdf   = new CollectionElementDummy(1, 'asdf', 'value1'),
            $zxcv   = new CollectionElementDummy(2, 'qwer', 'value2'),
            $qwer   = new CollectionElementDummy(3, 'qwer', 'value3'),
        ]);

        $this->assertSame(['asdf' => [$asdf], 'qwer' => [$zxcv, $qwer]], $collection->toMap(['group']));

        $this->assertSame(['asdf' => $asdf, 'qwer' => $zxcv], $collection->toOneMap(['group']));
    }

    #[Test]
    public function staticMethods(): void
    {
        $this->assertSame(1, CollectionDummy::createUniqueId(new CollectionElementDummy(1, 'asdf', 'value1')));
        $this->assertSame(2, CollectionDummy::createUniqueId(new CollectionElementDummy(2, 'zxcv', 'value2')));

        $this->assertSame(1, CollectionDummy::extractUniqueId(new CollectionElementDummy(1, 'asdf', 'value1')));
        $this->assertSame(2, CollectionDummy::extractUniqueId(new CollectionElementDummy(2, 'zxcv', 'value2')));

        $this->assertSame(CollectionElementDummy::class, CollectionDummy::getAllowedClass());

        $this->assertTrue(CollectionDummy::isAllowedClass(new CollectionElementDummy(1, 'asdf', 'value1')));
        $this->assertTrue(CollectionDummy::isAllowedClass(CollectionElementDummy::class));
        $this->assertFalse(CollectionDummy::isAllowedClass(\SplFileObject::class));

        $this->assertSame(1, CollectionDummy::adjustKey(1));

        $this->assertSame(1, CollectionDummy::adjustKey(new class() {
            public int $value = 1;
        }));
    }
}
