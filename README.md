# TacDDD: template for Tactical DDD

TacDDD（タックディー）は戦術的DDDの迅速な立ち上げを支援するためのPHPテンプレートパッケージです。

対象となるバージョンはPHP8.2.0以上です。

# collections

兎に角に何もせずコレクションから有用な値を引き出す事に特化した特性を用意しています。

## object collection

次の特性を使う事により、容易に任意の階層構造として値を取り出すことができます。

```php
use tacddd\collections\traits\objects\ObjectCollectionInterface;
use tacddd\collections\traits\objects\ObjectCollectionTrait;

final class ElementCollection implements ObjectCollectionInterface
{
    use ObjectCollectionTrait;

    // このコレクションが受け入れるクラスやインターフェースの設定
    public static function getAllowedClasses(): string|array
    {
        return Element::class;
    }

    // 受け入れたオブジェクトからユニークなキーを返す
    public static function createUniqueKey(object $element): string|int
    {
        return $element->getId();
    }
}
```

エンティティなど、対象が値オブジェクトを持ちgetterから取得した値から直接`string|int`を引けない場合は、次の`adjustKey`も追加してください。

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
        return $key->value;
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

$elementCollection->get(1); // $alphaを取得できる

$elementCollection->get(1); // $alphaを取得できる

$elementCollection->getByGroupInName('bravo', 'にほへ'); // $bravoを取得できる
$elementCollection->getByGroupInName(['bravo', 'にほへ']); // $bravoを取得できる
```

グルーピングした結果を取得したい場合は、`groupBy`プリフィックスを利用してください。

```php
$elementCollection->getByGroupInNameInId(); // 次の形式の配列を取得できる
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

## object collection factory

**コレクションクラスを作るのが手間**。わかります。

ファクトリ経由の無名クラスとしてコレクションの生成が可能です。

受け入れ可能クラスとユニークIDの指定のみは流石に記述が必要です。

```php
use tacddd\collections\MagicalAccessCollectionFactory;

$collection = MagicalAccessCollectionFactory::createObjectCollection(Element::class, function(Element $element): string|int {
    return $element->getId();
}, [
    new Element(1, 'alpha', 'いろは'),
    new Element(2, 'bravo', 'にほへ'),
]);
```

クロージャだと要求が明確にならないので不安という方は`UniqueKeyFactoryInterface`をご利用ください。

```php
use tacddd\collections\MagicalAccessCollectionFactory;
use tacddd\collections\interfaces\objects\UniqueKeyFactoryInterface;

$collection = MagicalAccessCollectionFactory::createObjectCollection(
    Element::class,
    new class() implements UniqueKeyFactoryInterface {
        /**
         * 指定されたオブジェクトからユニークキーを返します。
         *
         * @param  Element      $element オブジェクト
         * @return int|string   ユニークキー
         */
        public static function createUniqueKey(object $element): string|int
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
