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

namespace tacddd\tests\collections\objects\traits;

use PHPUnit\Framework\Attributes\Test;
use tacddd\tests\utilities\resources\dummy\objects\CollectionEntityDummy;
use tacddd\tests\utilities\resources\dummy\objects\CollectionMagicalDummy;
use tacddd\tests\utilities\test_cases\AbstractTestCase;

/**
 * @internal
 */
class MagicalAccessableObjectCollectionTest extends AbstractTestCase
{
    #[Test]
    public function methodNotFoundError(): void
    {
        $this->expectException(\Error::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            \sprintf('Call to undefined method %s::ffindToMapByGroupInId', CollectionMagicalDummy::class),
        );

        $collection     = new CollectionMagicalDummy([
            $asdf   = new CollectionEntityDummy(1, 'asdf', 'value1'),
            $zxcv   = new CollectionEntityDummy(2, 'zxcv', 'value2'),
            $qwer   = new CollectionEntityDummy(3, 'qwer', 'value3'),
        ]);

        $collection->ffindToMapByGroupInId('zxcv');
    }

    #[Test]
    public function argumentCountError(): void
    {
        $this->expectException(\ArgumentCountError::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessageMatches(
            \sprintf('/^Too few arguments to function %s, 1 passed in .+ on line \d+ and exactly 2 expected$/u', \preg_quote(\sprintf('%s::findToMapByGroupInId()', CollectionMagicalDummy::class))),
        );

        $collection     = new CollectionMagicalDummy([
            $asdf   = new CollectionEntityDummy(1, 'asdf', 'value1'),
            $zxcv   = new CollectionEntityDummy(2, 'zxcv', 'value2'),
            $qwer   = new CollectionEntityDummy(3, 'qwer', 'value3'),
        ]);

        $collection->findToMapByGroupInId('zxcv');
    }

    #[Test]
    public function toMap(): void
    {
        $collection     = new CollectionMagicalDummy([
            $asdf   = new CollectionEntityDummy(1, 'asdf', 'value1'),
            $zxcv   = new CollectionEntityDummy(2, 'qwer', 'value2'),
            $qwer   = new CollectionEntityDummy(3, 'qwer', 'value3'),
        ]);

        $this->assertSame(['asdf' => [$asdf], 'qwer' => [$zxcv, $qwer]], $collection->toMapInGroup());

        $this->assertSame(['asdf' => $asdf, 'qwer' => $zxcv], $collection->toOneMapInGroup());
    }

    #[Test]
    public function collection(): void
    {
        $collection     = new CollectionMagicalDummy([
            $asdf   = new CollectionEntityDummy(1, 'asdf', 'value1'),
            $zxcv   = new CollectionEntityDummy(2, 'zxcv', 'value2'),
            $qwer   = new CollectionEntityDummy(3, 'qwer', 'value3'),
        ]);

        // init
        $this->assertSame($zxcv, $collection->find(2));
        $this->assertSame([$zxcv], $collection->findById(2));
        $this->assertSame([$zxcv], $collection->findBy(['id' => 2]));

        $this->assertSame($zxcv, $collection->findOneById(2));
        $this->assertSame($zxcv, $collection->findOneBy(['id' => 2]));

        $this->assertSame(['zxcv' => [2 => [$zxcv]]], $collection->findToMapBy(['id' => 2], ['group', 'id']));
        $this->assertSame(['zxcv' => [2 => [$zxcv]]], $collection->findToMapByGroupInId('zxcv', 2));

        $this->assertSame(['zxcv' => [2 => $zxcv]], $collection->findOneToMapBy(['id' => 2], ['group', 'id']));
        $this->assertSame(['zxcv' => [2 => $zxcv]], $collection->findOneToMapByGroupInId('zxcv', 2));

        // add
        $collection->add($hjkl = new CollectionEntityDummy(4, 'qwer', 'value4'));

        $this->assertSame([$zxcv], $collection->findById(2));
        $this->assertSame($zxcv, $collection->findOneById(2));
        $this->assertSame(['zxcv' => [2 => [$zxcv]]], $collection->findToMapByGroupInId('zxcv', 2));
        $this->assertSame(['zxcv' => [2 => $zxcv]], $collection->findOneToMapByGroupInId('zxcv', 2));

        $this->assertSame(['qwer' => [3 => [$qwer], 4 => [$hjkl]]], $collection->findToMapByGroup('qwer', ['group', 'id']));

        $this->assertSame(['qwer' => [3 => $qwer, 4 => $hjkl]], $collection->findOneToMapByGroup('qwer', ['group', 'id']));

        $this->assertSame(['qwer' => [$qwer, $hjkl]], $collection->findToMapByGroup('qwer'));
        $this->assertSame(['qwer' => $qwer], $collection->findOneToMapByGroup('qwer'));

        // set
        $collection->add($nm = new CollectionEntityDummy(2, 'nm', 'value2_2'));

        $this->assertSame([$nm], $collection->findById(2));
        $this->assertSame($nm, $collection->findOneById(2));

        $this->assertSame(['nm' => [2 => [$nm]]], $collection->findToMapById(2, ['group', 'id']));
        $this->assertSame(['nm' => [2 => $nm]], $collection->findOneToMapById(2, ['group', 'id']));

        $this->assertSame(['qwer' => [3 => [$qwer], 4 => [$hjkl]]], $collection->findToMapByGroup('qwer', ['group', 'id']));
        $this->assertSame(['qwer' => [3 => $qwer, 4 => $hjkl]], $collection->findOneToMapByGroup('qwer', ['group', 'id']));

        // remove
        $collection->remove($zxcv); // unique idがマッチするオブジェクトの削除のため、zxcvでもnmでも同じ効用となる

        $this->assertFalse($collection->empty());
        $this->assertSame(3, $collection->count());

        $this->assertSame($asdf, $collection->first());
        $this->assertSame($hjkl, $collection->last());

        $this->assertSame([], $collection->findById(2));
        $this->assertSame(null, $collection->findOneById(2));
        $this->assertSame([], $collection->findToMapById(2, ['group', 'id']));

        $this->assertSame(['qwer' => [3 => [$qwer], 4 => [$hjkl]]], $collection->findToMapByGroup('qwer', ['group', 'id']));
        $this->assertSame(['qwer' => [3 => $qwer, 4 => $hjkl]], $collection->findOneToMapByGroup('qwer', ['group', 'id']));
    }
}
