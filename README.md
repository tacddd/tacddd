# TacDDD: template for Tactical DDD

TacDDD（タックディー）は戦術的DDDの迅速な立ち上げを支援するためのPHPテンプレートパッケージです。

対象となるバージョンはPHP8.2.0以上です。

# collections

兎に角に何もせずコレクションから有用な値を引き出す事に特化した特性を用意しています。

## entity collection

次の特性を使う事により、容易に任意の階層構造として値を取り出すことができます。

```php
use tacddd\collections\entities\traits\EntityCollectionInterface;
use tacddd\collections\entities\traits\EntityCollectionTrait;

final class ElementCollection implements EntityCollectionInterface
{
    use EntityCollectionTrait;

    // このコレクションが受け入れるクラスやインターフェースの設定
    public static function getAllowedClass(): string
    {
        return Element::class;
    }

    // 受け入れたオブジェクトからユニークなキーを返す
    public static function createUniqueId(object $element): string|int
    {
        return $element->getId();
    }
}
```

対象が値オブジェクトを持ちgetterから取得した値から直接`string|int`を引けない場合は、次の`adjustKey`も追加してください。

```php
    /**
     * キーがstring|intではなかった場合に調整して返します。
     *
     * @param  mixed      $key キー
     * @return string|int 調整済みキー
     */
    public static function adjustKey(mixed $key): string|int
    {
        // 値オブジェクトが仮にpublic readonly string $value;を持つ場合
        if (\is_object($key)) {
            return $key->value;
        }
    }
```

`Element`クラスが次のインターフェースを持っていた場合、その後に続くデータアクセスが可能となります。

```php
final class Element
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
$elementCollection = new ElementCollection();

$alpha  = new Element(1, 'alpha', 'いろは');
$elementCollection->add($alpha);

$bravo  = new Element(2, 'bravo', 'にほへ');
$elementCollection->add($bravo);

$charley    = new Element(3, 'bravo', 'とちり');
$elementCollection->add($charley);

$elementCollection->find(1); // $alphaを取得できる

$elementCollection->findBy(['name' => 'bravo']); // [$bravo, $charley]を取得できる
```

グルーピングした結果を取得したい場合は、`toMap`メソッドを利用してください。

```php
$elementCollection->toMap(['group', 'name', 'id']); // 次の形式の配列を取得できる
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
$elementCollection->toOneMap(['group', 'name', 'id']); // 次の形式の配列を取得できる
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

## magical access entity collection

次の特性を追加する事により、メソッドとして自明的なアクセスも可能になります。

```php
use tacddd\collections\entities\traits\EntityCollectionInterface;
use tacddd\collections\entities\traits\EntityCollectionTrait;
use tacddd\collections\entities\traits\magical_accesser\EntityCollectionMagicalAccessorInterface;
use tacddd\collections\entities\traits\magical_accesser\EntityCollectionMagicalAccessorTrait;

final class MagicalElementCollection implements EntityCollectionInterface, EntityCollectionMagicalAccessorInterface
{
    use EntityCollectionTrait;
    use EntityCollectionMagicalAccessorTrait;

    // このコレクションが受け入れるクラスやインターフェースの設定
    public static function getAllowedClass(): string
    {
        return Element::class;
    }

    // 受け入れたオブジェクトからユニークなキーを返す
    public static function createUniqueId(object $element): string|int
    {
        return $element->getId();
    }
}
```

次のように引数に引きずられる事なくアクセスができます。

```php
$elementCollection = new ElementCollection();

$alpha  = new Element(1, 'alpha', 'いろは');
$elementCollection->add($alpha);

$bravo  = new Element(2, 'bravo', 'にほへ');
$elementCollection->add($bravo);

$charley    = new Element(3, 'bravo', 'とちり');
$elementCollection->add($charley);

$elementCollection->findOneByName('alpha'); // $alphaを取得できる

$elementCollection->findByName('bravo'); // [$bravo, $charley]を取得できる

$elementCollection->toMapInGroupInNameInId(); // 次の形式の配列を取得できる
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

$elementCollection->toOneMapInGroupInNameInId(); // 次の形式の配列を取得できる
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

## entity collection factory

**コレクションクラスを作るのが手間**。わかります。

ファクトリ経由の無名クラスとしてコレクションの生成が可能です。

受け入れ可能クラスとユニークIDの指定のみは流石に記述が必要です。

```php
use tacddd\collections\entities\MagicalAccessEntityCollectionFactory;

$collection = MagicalAccessEntityCollectionFactory::createEntityCollection(Element::class, function(Element $element): string|int {
    return $element->getId();
}, [
    new Element(1, 'alpha', 'いろは'),
    new Element(2, 'bravo', 'にほへ'),
]);
```

クロージャだと要求が明確にならないので不安という方は`UniqueIdFactoryInterface`をご利用ください。

```php
use tacddd\collections\entities\MagicalAccessEntityCollectionFactory;
use tacddd\collections\entities\interfaces\UniqueIdFactoryInterface;

$collection = MagicalAccessEntityCollectionFactory::createEntityCollection(
    Element::class,
    new class() implements UniqueIdFactoryInterface {
        /**
         * 指定されたオブジェクトからユニークIDを返します。
         *
         * @param  Element      $element オブジェクト
         * @return int|string   ユニークID
         */
        public static function createUniqueId(object $element): string|int
        {
            return $element->getId();
        }
    },
    [
        new Element(1, 'alpha', 'いろは'),
        new Element(2, 'bravo', 'にほへ'),
    ],
);
```
