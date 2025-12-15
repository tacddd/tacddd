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
        $this->assertEquals(new CollectionDummy([$zxcv]), $collection->findBy(['id' => 2]));
        $this->assertSame($zxcv, $collection->findOneBy(['id' => 2]));
        $this->assertSame(['zxcv' => [2 => [$zxcv]]], $collection->findToMapBy(['id' => 2], ['group', 'id']));
        $this->assertSame(['zxcv' => [2 => $zxcv]], $collection->findOneToMapBy(['id' => 2], ['group', 'id']));

        $this->assertSame($zxcv, $collection->find($zxcv));
        $this->assertEquals(new CollectionDummy([$zxcv]), $collection->findBy(['id' => $zxcv]));
        $this->assertSame($zxcv, $collection->findOneBy(['id' => $zxcv]));

        // add
        $collection->add($hjkl = new CollectionEntityDummy(4, 'qwer', 'value4'));

        $this->assertFalse($collection->empty());
        $this->assertSame(4, $collection->count());

        $this->assertSame($asdf, $collection->first());
        $this->assertSame($hjkl, $collection->last());

        $this->assertSame($zxcv, $collection->find(2));
        $this->assertEquals(new CollectionDummy([$zxcv]), $collection->findBy(['id' => 2]));
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

        $this->assertEquals(new CollectionDummy([$nm]), $collection->findBy(['id' => 2]));
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

        $this->assertNull($collection->find(2));

        $this->assertEquals(new CollectionDummy([]), $collection->findBy(['id' => 2]));
        $this->assertNull($collection->findOneBy(['id' => 2]));
        $this->assertSame([], $collection->findToMapBy(['id' => 2], ['group', 'id']));

        $this->assertSame(['qwer' => [3 => [$qwer], 4 => [$hjkl]]], $collection->findToMapBy(['group' => 'qwer'], ['group', 'id']));
        $this->assertSame(['qwer' => [3 => $qwer, 4 => $hjkl]], $collection->findOneToMapBy(['group' => 'qwer'], ['group', 'id']));
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
        $this->assertEquals(new EntityCollectionPropertyAccessDummy([$zxcv]), $collection->findBy(['id' => 2]));
        $this->assertSame($zxcv, $collection->findOneBy(['id' => 2]));
        $this->assertSame(['zxcv' => [2 => [$zxcv]]], $collection->findToMapBy(['id' => 2], ['group', 'id']));
        $this->assertSame(['zxcv' => [2 => $zxcv]], $collection->findOneToMapBy(['id' => 2], ['group', 'id']));

        $this->assertSame($zxcv, $collection->find($zxcv));
        $this->assertEquals(new EntityCollectionPropertyAccessDummy([$zxcv]), $collection->findBy(['id' => $zxcv]));
        $this->assertSame($zxcv, $collection->findOneBy(['id' => $zxcv]));
    }

    #[Test]
    public function findValue(): void
    {
        $collection = new CollectionDummy([
            $asdf = new CollectionEntityDummy(1, 'asdf', 'value1'),
            $zxcv = new CollectionEntityDummy(2, 'zxcv', 'value2'),
            $qwer = new CollectionEntityDummy(3, 'zxcv', 'value3'),
        ]);

        $this->assertSame('value1', $collection->findValue(1, 'name'));

        $this->assertSame(
            [1 => 'asdf', 2 => 'zxcv', 3 => 'zxcv'],
            $collection->findValueAll('group'),
        );

        $this->assertSame(
            ['value2', 'value3'],
            $collection->findValueByAsArray(['group' => 'zxcv'], 'name'),
        );

        $this->assertSame(
            'value2',
            $collection->findValueOneBy(['group' => 'zxcv'], 'name'),
        );
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

        $expected       = [
            'asdf' => [
                'value1'    => [
                    1   => [
                        $asdf,
                    ],
                ],
            ],
            'qwer' => [
                'value2'    => [
                    2   => [
                        $zxcv,
                    ],
                ],
                'value3'    => [
                    3 => [
                        $qwer,
                    ],
                ],
            ],
            '1111' => [
                'value3'    => [
                    4 => [
                        $obj1,
                    ],
                    5 => [
                        $obj2,
                    ],
                    6 => [
                        $obj3,
                    ],
                ],
            ],
        ];

        $this->assertSame($expected, $collection->toMap(['group', 'name', 'id']));

        // ----------------------------------------------
        $collection->remove($zxcv);
        $collection->remove($qwer);

        $expected       = [
            'asdf' => [
                'value1'    => [
                    1   => [
                        $asdf,
                    ],
                ],
            ],
            '1111' => [
                'value3'    => [
                    4 => [
                        $obj1,
                    ],
                    5 => [
                        $obj2,
                    ],
                    6 => [
                        $obj3,
                    ],
                ],
            ],
        ];

        $this->assertSame($expected, $collection->toMap(['group', 'name', 'id']));
    }

    #[Test]
    public function toOneMap(): void
    {
        $collection     = new CollectionDummy([
            $asdf   = new CollectionEntityDummy(1, 'asdf', 'value1'),
            $zxcv   = new CollectionEntityDummy(2, 'qwer', 'value2'),
            $qwer   = new CollectionEntityDummy(3, 'qwer', 'value3'),
            $obj1   = new CollectionEntityDummy(4, '1111', 'value3'),
            $obj2   = new CollectionEntityDummy(5, '1111', 'value3'),
            $obj3   = new CollectionEntityDummy(6, '1111', 'value3'),
        ]);

        $expected       = [
            'asdf' => [
                'value1'    => [
                    1   => $asdf,
                ],
            ],
            'qwer' => [
                'value2'    => [
                    2   => $zxcv,
                ],
                'value3'    => [
                    3 => $qwer,
                ],
            ],
            '1111' => [
                'value3'    => [
                    4 => $obj1,
                    5 => $obj2,
                    6 => $obj3,
                ],
            ],
        ];
        $this->assertSame($expected, $collection->toOneMap(['group', 'name', 'id']));

        // ----------------------------------------------
        $collection->remove($zxcv);
        $collection->remove($qwer);

        $expected       = [
            'asdf' => [
                'value1'    => [
                    1   => $asdf,
                ],
            ],
            '1111' => [
                'value3'    => [
                    4 => $obj1,
                    5 => $obj2,
                    6 => $obj3,
                ],
            ],
        ];
        $this->assertSame($expected, $collection->toOneMap(['group', 'name', 'id']));
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
            'asdf'    => [
                1   => [
                    [
                        'group' => 'asdf',
                        'id'    => 1,
                    ],
                ],
            ],
            'zxcv'    => [
                2   => [
                    [
                        'group' => 'zxcv',
                        'id'    => 2,
                    ],
                ],
                3   => [
                    [
                        'group' => 'zxcv',
                        'id'    => 3,
                    ],
                ],
            ],
        ];
        $this->assertSame($expected, $actual->toArrayMap(['group', 'id']));
    }

    #[Test]
    public function toArrayOneMap(): void
    {
        $actual = new CollectionDummy([
            $asdf = new CollectionEntityDummy(1, 'asdf', 'value1'),
            $zxcv = new CollectionEntityDummy(2, 'zxcv', 'value2'),
            $qwer = new CollectionEntityDummy(3, 'zxcv', 'value3'),
        ]);

        $expected = [
            'asdf' => [
                1 => [
                    'group' => 'asdf',
                    'id'    => 1,
                ],
            ],
            'zxcv' => [
                2 => [
                    'group' => 'zxcv',
                    'id'    => 2,
                ],
                3 => [
                    'group' => 'zxcv',
                    'id'    => 3,
                ],
            ],
        ];

        $this->assertSame($expected, $actual->toArrayOneMap(['group', 'id']));
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
            1   => 1,
            2   => 2,
            3   => 3,
        ];
        $this->assertSame($expected, $actual->getArrayMap(['id']));

        $expected   = [
            'asdf'    => [
                1   => 'asdf',
            ],
            'zxcv'    => [
                2   => 'zxcv',
                3   => 'zxcv',
            ],
        ];
        $this->assertSame($expected, $actual->getArrayMap(['group', 'id']));

        $actual   = new CollectionDateDummy([
            $asdf   = new CollectionDateEntityDummy(1, $asdf_date = new \DateTimeImmutable('2023-01-01')),
            $zxcv   = new CollectionDateEntityDummy(2, $zxcv_date = new \DateTimeImmutable('2023-01-02')),
            $qwer   = new CollectionDateEntityDummy(3, $qwer_date = new \DateTimeImmutable('2023-01-02')),
        ]);

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

        $expected   = [
            '2023/01/01'    => [
                1 => $asdf_date,
            ],
            '2023/01/02'    => [
                2 => $zxcv_date,
                3 => $qwer_date,
            ],
        ];
        $this->assertSame($expected, $actual->getArrayMap(['date_time', 'id']));
    }

    #[Test]
    public function findByOr(): void
    {
        $collection = new CollectionDummy([
            $a = new CollectionEntityDummy(1, 'asdf', 'value1'),
            $b = new CollectionEntityDummy(2, 'zxcv', 'value2'),
            $c = new CollectionEntityDummy(3, 'qwer', 'value3'),
            $d = new CollectionEntityDummy(4, 'zxcv', 'value4'),
            $e = new CollectionEntityDummy(5, 'hjkl', 'value5'),
        ]);

        // ==============================================
        $this->assertEquals(
            new CollectionDummy([$b, $c, $d]),
            $collection->findBy([
                'group' => ['zxcv', 'qwer'],
            ]),
        );

        // ==============================================
        $this->assertEquals(
            new CollectionDummy([$b, $d]),
            $collection->findBy([
                'group' => ['zxcv', 'qwer'],
                'id'    => [2, 4],
            ]),
        );

        // ==============================================
        $this->assertEquals(
            new CollectionDummy([$b, $d]),
            $collection->findBy([
                'id' => [$b, $d],
            ]),
        );

        // ==============================================
        $one = $collection->findOneBy([
            'group' => ['zxcv', 'qwer'],
        ]);

        $this->assertTrue(
            $one === $b || $one === $c || $one === $d,
        );

        // ==============================================
        $this->assertEquals(
            (new CollectionDummy([$b])),
            $collection->findBy([
                'id'    => [1, 2],
                'group' => ['zxcv', 'qwer'],
            ]),
        );

        // ==============================================
        $this->assertEquals(
            (new CollectionDummy([$b, $d]))->toArray(),
            $collection->findBy([
                'group' => 'zxcv',
                'name'  => ['value2', 'value4'],
            ])->toArray(),
        );
    }
}
