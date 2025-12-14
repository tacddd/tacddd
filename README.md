# TacDDD: template for Tactical DDD

TacDDD（タックディー）は戦術的DDDの迅速な立ち上げを支援するためのPHPテンプレートパッケージです。

対象となるバージョンはPHP8.2.0以上です。

# collections

兎に角に何もせずコレクションから有用な値を引き出す事に特化した特性を用意しています。

## entity collection

次の特性を使う事により、容易に任意の階層構造として値を取り出すことができます。

```php
use tacddd\collections\objects\traits\ObjectCollectionInterface;
use tacddd\collections\objects\traits\ObjectCollectionTrait;

// 仮にエンティティのコレクションとする
final class EntityCollection implements ObjectCollectionInterface
{
    use ObjectCollectionTrait;

    // このコレクションが受け入れるクラスやインターフェースの設定
    public static function getAllowedClass(): string
    {
        return Entity::class;
    }

    // 受け入れた値（オブジェクト）からユニークなキーを返す
    public static function createUniqueId(mixed $value): string|int
    {
        return $value->getId();
    }
}
```

対象が値オブジェクトを持ちgetterから取得した値から直接`string|int`を引けない場合は、次の`normalizeKey`も追加してください。

```php
   /**
     * キーがstring|intではなかった場合に調整して返します。
     *
     * @param  mixed       $key        キー
     * @param  null|string $access_key アクセスキー
     * @return string|int  調整済みキー
     */
    public static function normalizeKey(mixed $key, ?string $access_key = null): string|int
    {
        // 値オブジェクトが仮にpublic readonly string $value;を持つ場合
        if (\is_object($key)) {
            return $key->value;
        }
    }
```

`Entity`クラスが次のインターフェースを持っていた場合、その後に続くデータアクセスが可能となります。

```php
final class Entity
{
    public function __construct(
        private int $id,
        private string $group,
        private string $name,
    )
    {}

    public function getId()
    {
        return $this->id;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function getName()
    {
        return $this->name;
    }
}
```

```php
$entityCollection = new EntityCollection();

$alpha  = new Entity(1, 'alpha', 'いろは');
$entityCollection->add($alpha);

$bravo  = new Entity(2, 'bravo', 'にほへ');
$entityCollection->add($bravo);

$charley    = new Entity(3, 'bravo', 'とちり');
$entityCollection->add($charley);

$entityCollection->find(1); // $alphaを取得できる

$entityCollection->findBy(['name' => 'bravo']); // [$bravo, $charley]を取得できる
```

グルーピングした結果を取得したい場合は、`toMap`メソッドを利用してください。

```php
$entityCollection->toMap(['group', 'name', 'id']); // 次の形式の配列を取得できる
/*
[
    'alpha' => [
        'いろは'    => [
            1   => [
                $alpha
            ],
        ]
    ],
    'bravo' => [
        'にほへ'    => [
            2   => [
                $bravo
            ],
        ],
        'とちり'    => [
            3   => [
                $charley
            ],
        ],
    ],
]
*/
```

末端が単一要素なグルーピングした結果を取得したい場合は、`toOneMap`メソッドを利用してください。

```php
$entityCollection->toOneMap(['group', 'name', 'id']); // 次の形式の配列を取得できる
/*
[
    'alpha' => [
        'いろは'    => [
            1   => $alpha,
        ]
    ],
    'bravo' => [
        'にほへ'    => [
            2   => $bravo,
        ],
        'とちり'    => [
            3   => $charley,
        ],
    ],
]
*/
```

値だけの配列としてグルーピングした結果を取得したい場合は、`toArrayMap`メソッドを利用してください。

```php
$entityCollection->toArrayMap(['group', 'name', 'id']); // 次の形式の配列を取得できる
/*
[
    'alpha' => [
        'いろは'    => [
            1   => [
                [
                    'group' => 'alpha',
                    'name'  => 'いろは',
                    'id'    => 1,
                ],
            ],
        ]
    ],
    'bravo' => [
        'にほへ'    => [
            2   => [
                [
                    'group' => 'bravo',
                    'name'  => 'にほへ',
                    'id'    => 2,
                ],
            ],
        ],
        'とちり'    => [
            3   => [
                [
                    'group' => 'bravo',
                    'name'  => 'とちり',
                    'id'    => 3,
                ],
            ],
        ],
    ],
]
*/
```

値だけの配列として第一要素だけをグルーピングした結果を取得したい場合は、`toArrayOneMap`メソッドを利用してください。

```php
$entityCollection->toArrayOneMap(['group', 'name', 'id']); // 次の形式の配列を取得できる
/*
[
    'alpha' => [
        'いろは'    => [
            1   => [
                'group' => 'alpha',
                'name'  => 'いろは',
                'id'    => 1,
            ],
        ]
    ],
    'bravo' => [
        'にほへ'    => [
            2   => [
                'group' => 'bravo',
                'name'  => 'にほへ',
                'id'    => 2,
            ],
        ],
        'とちり'    => [
            3   => [
                'group' => 'bravo',
                'name'  => 'とちり',
                'id'    => 3,
            ],
        ],
    ],
]
*/
```

値だけのマップとしてグルーピングした結果を取得したい場合は、`getArrayMap`メソッドを利用してください。

```php
$entityCollection->getArrayMap(['id']); // 次の形式の配列を取得できる
/*
[
    1   => 1,
    2   => 2,
    3   => 3,
]
*/
```

第二引数を利用することで値を変更することもできます。

```php
$entityCollection->getArrayMap(['group', 'name', 'id'], 'name'); // 次の形式の配列を取得できる
/*
[
    'alpha' => [
        'いろは'    => [
            1   => 'いろは',
        ]
    ],
    'bravo' => [
        'にほへ'    => [
            2   => 'にほへ',
        ],
        'とちり'    => [
            3   => 'とちり',
        ],
    ],
]
*/
```

第二引数にコールバックを指定することで様々な変更も実施できます。

```php
$entityCollection->getArrayMap(['group', 'name', 'id'], function (
    Entity $entity,
    array $access_key_cache,
): int|string {
    return $entity->getName();
}); // 次の形式の配列を取得できる
/*
[
    'alpha' => [
        'いろは'    => [
            1   => 'いろは',
        ]
    ],
    'bravo' => [
        'にほへ'    => [
            2   => 'にほへ',
        ],
        'とちり'    => [
            3   => 'とちり',
        ],
    ],
]
*/
```

## magical access entity collection

次の特性を追加する事により、メソッドとして自明的なアクセスも可能になります。

```php
use tacddd\collections\objects\traits\ObjectCollectionInterface;
use tacddd\collections\objects\traits\ObjectCollectionTrait;
use tacddd\collections\objects\traits\magical_accesser\ObjectCollectionMagicalAccessorInterface;
use tacddd\collections\objects\traits\magical_accesser\ObjectCollectionMagicalAccessorTrait;

final class MagicalEntityCollection implements
    ObjectCollectionInterface,
    ObjectCollectionMagicalAccessorInterface
{
    use ObjectCollectionTrait;
    use ObjectCollectionMagicalAccessorTrait;

    // このコレクションが受け入れるクラスやインターフェースの設定
    public static function getAllowedClass(): string
    {
        return Entity::class;
    }

    // 受け入れたオブジェクトからユニークなキーを返す
    public static function createUniqueId(mixed $value): string|int
    {
        return $value->getId();
    }
}
```

次のように引数に引きずられる事なくアクセスができます。

```php
$entityCollection = new EntityCollection();

$alpha  = new Entity(1, 'alpha', 'いろは');
$entityCollection->add($alpha);

$bravo  = new Entity(2, 'bravo', 'にほへ');
$entityCollection->add($bravo);

$charley    = new Entity(3, 'bravo', 'とちり');
$entityCollection->add($charley);

$entityCollection->findOneByName('alpha'); // $alphaを取得できる

$entityCollection->findByName('bravo'); // [$bravo, $charley]を取得できる

$entityCollection->toMapInGroupInNameInId(); // 次の形式の配列を取得できる
/*
[
    'alpha' => [
        'いろは'    => [
            1   => [
                $alpha
            ],
        ]
    ],
    'bravo' => [
        'にほへ'    => [
            2   => [
                $bravo
            ],
        ],
        'とちり'    => [
            3   => [
                $charley
            ],
        ],
    ],
]
*/

$entityCollection->toOneMapInGroupInNameInId(); // 次の形式の配列を取得できる
/*
[
    'alpha' => [
        'いろは'    => [
            1   => $alpha,
        ]
    ],
    'bravo' => [
        'にほへ'    => [
            2   => $bravo,
        ],
        'とちり'    => [
            3   => $charley,
        ],
    ],
]
*/
```

その他、前述のメソッドは次の形で代替できます。

```php
// 次の二つは等価
$entityCollection->toArrayMap(['group', 'name', 'id']);
$entityCollection->toArrayMapOfGroupAndNameAndId();

// 次の二つは等価
$entityCollection->toArrayOneMap(['group', 'name', 'id']);
$entityCollection->toArrayOneMapOfGroupAndNameAndId();

// 次の二つは等価
$entityCollection->getArrayMap(['id']);
$entityCollection->getArrayMapOfId();

// 次の二つは等価
$entityCollection->getArrayMap(['group', 'name', 'id'], 'name');
$entityCollection->getArrayMapOfGroupAndNameAndId('name');

// 次の二つは等価
$entityCollection->getArrayMap(['group', 'name', 'id'], function (
    Entity $entity,
    array $access_key_cache,
): int|string {
    return $entity->getName();
});
$entityCollection->getArrayMapOfGroupAndNameAndId(function (
    Entity $entity,
    array $access_key_cache,
): int|string {
    return $entity->getName();
});
```

## collection factory

**コレクションクラスを作るのが手間**。わかります。

ファクトリ経由の無名クラスとしてコレクションの生成が可能です。

受け入れ可能クラスとユニークIDの指定のみは流石に記述が必要です。

```php
use tacddd\collections\CollectionFactory;

$collection = CollectionFactory::createForObject(
    class           : Entity::class,
    createUniqueId  : function(mixed $value): string|int {
        return $value->getId();
    },
    objects         : [
        new Entity(1, 'alpha', 'いろは'),
        new Entity(2, 'bravo', 'にほへ'),
    ]
);
```

クロージャだと要求が明確にならないので不安という方は`UniqueIdFactoryInterface`をご利用ください。

```php
use tacddd\collections\CollectionFactory;
use tacddd\collections\interfaces\UniqueIdFactoryInterface;

$collection = CollectionFactory::createForObject(
    class           : Entity::class,
    createUniqueId  : new class() implements UniqueIdFactoryInterface {
        public static function createUniqueId(mixed $value): string|int
        {
            return $value->getId();
        }
    },
    objects         : [
        new Entity(1, 'alpha', 'いろは'),
        new Entity(2, 'bravo', 'にほへ'),
    ],
);
```

### tips

とりあえずuniqueも何も関係なくオブジェクトを投入したい場合は、ユニークIDの抽出に`spl_object_id`を利用してみてください。

```
        public static function createUniqueId(mixed $value): string|int
        {
            return \spl_object_id($value);
        }
```