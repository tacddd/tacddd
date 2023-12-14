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

namespace tacddd\tests\cases\collections\objects\traits;

use PHPUnit\Framework\Attributes\Test;
use tacddd\tests\utilities\AbstractTestCase;
use tacddd\tests\utilities\resources\dummy\collections\EntityCollectionPropertyAccessDummy;
use tacddd\tests\utilities\resources\dummy\objects\CollectionDateDummy;
use tacddd\tests\utilities\resources\dummy\objects\CollectionDateEntityDummy;
use tacddd\tests\utilities\resources\dummy\objects\CollectionDummy;
use tacddd\tests\utilities\resources\dummy\objects\CollectionEntityDummy;
use tacddd\tests\utilities\resources\dummy\objects\CollectionEntityPropertyAccessDummy;

/**
 * @internal
 */
class ObjectCollectionTest extends AbstractTestCase
{
    #[Test]
    public function collection(): void
    {
        $collection     = new CollectionDummy([
            $asdf   = new CollectionEntityDummy(1, 'asdf', 'value1'),
            $zxcv   = new CollectionEntityDummy(2, 'zxcv', 'value2'),
            $qwer   = new CollectionEntityDummy(3, 'qwer', 'value3'),
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

        $this->assertSame($zxcv, $collection->find($zxcv));
        $this->assertSame([$zxcv], $collection->findBy(['id' => $zxcv]));
        $this->assertSame($zxcv, $collection->findOneBy(['id' => $zxcv]));

        // add
        $collection->add($hjkl = new CollectionEntityDummy(4, 'qwer', 'value4'));

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
        $collection->add($nm = new CollectionEntityDummy(2, 'nm', 'value2_2'));

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
            $asdf   = new CollectionEntityDummy(1, 'asdf', 'value1'),
            $zxcv   = new CollectionEntityDummy(2, 'qwer', 'value2'),
            $qwer   = new CollectionEntityDummy(3, 'qwer', 'value3'),
            $obj1   = new CollectionEntityDummy(4, '1111', 'value3'),
            $obj2   = new CollectionEntityDummy(5, '1111', 'value3'),
            $obj3   = new CollectionEntityDummy(6, '1111', 'value3'),
        ]);

        $collection->toOneMap(['group', 'name', 'id']);

        $this->assertSame(['asdf' => [$asdf], 'qwer' => [$zxcv, $qwer], '1111' => [$obj1, $obj2, $obj3]], $collection->toMap(['group']));

        $collection->remove($zxcv);
        $collection->remove($qwer);

        $this->assertSame(['asdf' => [$asdf], '1111' => [$obj1, $obj2, $obj3]], $collection->toMap(['group']));
    }

    #[Test]
    public function staticMethods(): void
    {
        $this->assertSame(1, CollectionDummy::createUniqueId(new CollectionEntityDummy(1, 'asdf', 'value1')));
        $this->assertSame(2, CollectionDummy::createUniqueId(new CollectionEntityDummy(2, 'zxcv', 'value2')));

        $this->assertSame(1, CollectionDummy::extractUniqueId(new CollectionEntityDummy(1, 'asdf', 'value1')));
        $this->assertSame(2, CollectionDummy::extractUniqueId(new CollectionEntityDummy(2, 'zxcv', 'value2')));

        $this->assertSame(CollectionEntityDummy::class, CollectionDummy::getAllowedClass());

        $this->assertTrue(CollectionDummy::isAllowedClass(new CollectionEntityDummy(1, 'asdf', 'value1')));
        $this->assertTrue(CollectionDummy::isAllowedClass(CollectionEntityDummy::class));
        $this->assertFalse(CollectionDummy::isAllowedClass(\SplFileObject::class));

        $this->assertSame(1, CollectionDummy::normalizeKey(1));

        $this->assertSame(1, CollectionDummy::normalizeKey(new class() {
            public int $value = 1;
        }));
    }

    #[Test]
    public function accessStyle(): void
    {
        $collection     = new EntityCollectionPropertyAccessDummy([
            $asdf   = new CollectionEntityPropertyAccessDummy(1, 'asdf', 'value1'),
            $zxcv   = new CollectionEntityPropertyAccessDummy(2, 'zxcv', 'value2'),
            $qwer   = new CollectionEntityPropertyAccessDummy(3, 'qwer', 'value3'),
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

        $this->assertSame($zxcv, $collection->find($zxcv));
        $this->assertSame([$zxcv], $collection->findBy(['id' => $zxcv]));
        $this->assertSame($zxcv, $collection->findOneBy(['id' => $zxcv]));
    }

    #[Test]
    public function findValue(): void
    {
        $collection     = new CollectionDummy([
            $asdf   = new CollectionEntityDummy(1, 'asdf', 'value1'),
            $zxcv   = new CollectionEntityDummy(2, 'zxcv', 'value2'),
            $qwer   = new CollectionEntityDummy(3, 'zxcv', 'value3'),
        ]);

        $this->assertSame('value1', $collection->findValue(1, 'name'));

        $this->assertSame([1 => 'asdf', 2 => 'zxcv', 3 => 'zxcv'], $collection->findValueAll('group'));

        $this->assertSame(['value2', 'value3'], $collection->findValueBy(['group' => 'zxcv'], 'name'));

        $this->assertSame('value2', $collection->findValueOneBy(['group' => 'zxcv'], 'name'));
    }

    #[Test]
    public function addAll(): void
    {
        $expected   = new CollectionDummy([
            $asdf   = new CollectionEntityDummy(1, 'asdf', 'value1'),
            $zxcv   = new CollectionEntityDummy(2, 'zxcv', 'value2'),
            $qwer   = new CollectionEntityDummy(3, 'zxcv', 'value3'),
        ]);

        $this->assertEquals(
            $expected,
            (new CollectionDummy())->addAll(
                $asdf   = new CollectionEntityDummy(1, 'asdf', 'value1'),
                $zxcv   = new CollectionEntityDummy(2, 'zxcv', 'value2'),
                $qwer   = new CollectionEntityDummy(3, 'zxcv', 'value3'),
            ),
        );

        $this->assertEquals(
            $expected,
            (new CollectionDummy())->addAll(
                [
                    $asdf   = new CollectionEntityDummy(1, 'asdf', 'value1'),
                ],
                $zxcv   = new CollectionEntityDummy(2, 'zxcv', 'value2'),
                $qwer   = new CollectionEntityDummy(3, 'zxcv', 'value3'),
            ),
        );

        $this->assertEquals(
            $expected,
            (new CollectionDummy())->addAll(
                $asdf   = new CollectionEntityDummy(1, 'asdf', 'value1'),
                [$zxcv   = new CollectionEntityDummy(2, 'zxcv', 'value2')],
                $qwer   = new CollectionEntityDummy(3, 'zxcv', 'value3'),
            ),
        );
    }

    #[Test]
    public function extractUniqueIdException(): void
    {
        $this->expectException(\Error::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'tacddd\tests\utilities\resources\dummy\objects\CollectionDummyに受け入れ可能外のクラスを指定されました。class:DateTimeImmutable, allowed_class:tacddd\tests\utilities\resources\dummy\objects\CollectionEntityDummy',
        );

        CollectionDummy::extractUniqueId(new \DateTimeImmutable());
    }

    #[Test]
    public function addException(): void
    {
        $this->expectException(\Error::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'tacddd\tests\utilities\resources\dummy\objects\CollectionDummyに受け入れ可能外のクラスを指定されました。class:DateTimeImmutable, allowed_class:tacddd\tests\utilities\resources\dummy\objects\CollectionEntityDummy',
        );

        (new CollectionDummy())->add(new \DateTimeImmutable());
    }

    #[Test]
    public function toArrayMap(): void
    {
        $actual   = new CollectionDummy([
            $asdf   = new CollectionEntityDummy(1, 'asdf', 'value1'),
            $zxcv   = new CollectionEntityDummy(2, 'zxcv', 'value2'),
            $qwer   = new CollectionEntityDummy(3, 'zxcv', 'value3'),
        ]);

        $expected   = [
            1   => [
                'value1'    => [
                    [
                        'id'   => 1,
                        'name' => 'value1',
                    ],
                ],
            ],
            2   => [
                'value2'    => [
                    [
                        'id'   => 2,
                        'name' => 'value2',
                    ],
                ],
            ],
            3   => [
                'value3'    => [
                    [
                        'id'   => 3,
                        'name' => 'value3',
                    ],
                ],
            ],
        ];
        $this->assertSame($expected, $actual->toArrayMap(['id', 'name']));

        $expected   = [
            1   => [
                [
                    'id' => 1,
                ],
            ],
            2   => [
                [
                    'id' => 2,
                ],
            ],
            3   => [
                [
                    'id' => 3,
                ],
            ],
        ];
        $this->assertSame($expected, $actual->toArrayMap(['id']));

        $expected   = [
            1   => [
                'value1'    => [
                    'id'   => 1,
                    'name' => 'value1',

                ],
            ],
            2   => [
                'value2'    => [
                    'id'   => 2,
                    'name' => 'value2',
                ],
            ],
            3   => [
                'value3'    => [
                    'id'   => 3,
                    'name' => 'value3',
                ],
            ],
        ];
        $this->assertSame($expected, $actual->toArrayOneMap(['id', 'name']));

        $expected   = [
            1   => [
                'id' => 1,
            ],
            2   => [
                'id' => 2,
            ],
            3   => [
                'id' => 3,
            ],
        ];
        $this->assertSame($expected, $actual->toArrayOneMap(['id']));
    }

    #[Test]
    public function getArrayMap(): void
    {
        $actual   = new CollectionDummy([
            $asdf   = new CollectionEntityDummy(1, 'asdf', 'value1'),
            $zxcv   = new CollectionEntityDummy(2, 'zxcv', 'value2'),
            $qwer   = new CollectionEntityDummy(3, 'zxcv', 'value3'),
        ]);

        $expected   = [
            1   => [
                'value1'    => 1,
            ],
            2   => [
                'value2'    => 2,
            ],
            3   => [
                'value3'    => 3,
            ],
        ];
        $this->assertSame($expected, $actual->getArrayMap(['id', 'name']));

        $expected   = [
            1   => 1,
            2   => 2,
            3   => 3,
        ];
        $this->assertSame($expected, $actual->getArrayMap(['id']));

        // ==============================================
        $actual   = new CollectionDateDummy([
            $asdf   = new CollectionDateEntityDummy(1, new \DateTimeImmutable('2023-01-01')),
            $zxcv   = new CollectionDateEntityDummy(2, new \DateTimeImmutable('2023-01-02')),
        ]);

        $expected   = [
            1   => [
                '2023/01/01'    => 1,
            ],
            2   => [
                '2023/01/02'    => 2,
            ],
        ];
        $this->assertSame($expected, $actual->getArrayMap(['id', 'date_time']));

        $expected   = [
            '2023/01/01'    => '2023/01/01',
            '2023/01/02'    => '2023/01/02',
        ];
        $this->assertSame($expected, $actual->getArrayMap(['date_time'], function(
            CollectionDateEntityDummy $entity,
            array $access_key_cache,
        ): string {
            return $entity->getDateTime()->format('Y/m/d');
        }));
    }
}
