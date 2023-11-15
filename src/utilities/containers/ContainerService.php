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

namespace tacddd\utilities\containers;

use tacddd\collections\traits\results\result_details\ResultDetailsCollectionInterface;
use tacddd\utilities\caching\interfaces\ValueObjectCacheServiceInterface;
use tacddd\utilities\caching\ValueObjectCacheService;
use tacddd\utilities\containers\services\Container;
use tacddd\utilities\containers\value_objects\ContainerAccessor;
use tacddd\utilities\containers\value_objects\ContainerAdapter;
use tacddd\utilities\containers\value_objects\traits\adapters\ContainerAdapterInterface;
use tacddd\utilities\converters\interfaces\StringServiceInterface;
use tacddd\utilities\converters\StringService;
use tacddd\utilities\results\collections\ResultDetailsCollection;
use tacddd\utilities\results\value_objects\Result;
use tacddd\utilities\results\value_objects\ResultDetails;
use tacddd\value_objects\results\result\traits\ResultInterface;
use tacddd\value_objects\results\result_details\traits\ResultDetailsInterface;

/**
 * 簡易コンテナサービス
 */
final class ContainerService
{
    /**
     * @var array インターフェースマップ
     */
    public const INTERFACE_MAP  = [
        ['id' => ValueObjectCacheServiceInterface::class,   'object' => ValueObjectCacheService::class, 'shared' => true],
        ['id' => ResultInterface::class,                    'object' => Result::class,                  'only_create' => true],
        ['id' => ResultDetailsInterface::class,             'object' => ResultDetails::class,           'only_create' => true],
        ['id' => ResultDetailsCollectionInterface::class,   'object' => ResultDetailsCollection::class, 'only_create' => true],
        ['id' => StringServiceInterface::class,             'object' => StringService::class,           'shared' => true],
    ];

    /**
     * @var ContainerAdapterInterface コンテナアダプタ
     */
    private static ContainerAdapterInterface $ADAPTER;

    /**
     * デフォルト設定を返します。
     *
     * @return iterable デフォルト設定
     */
    public static function getDefaultSettings(): iterable
    {
        return self::INTERFACE_MAP;
    }

    /**
     * コンテナアダプタを設定します。
     *
     * @return string このクラスパス
     */
    public static function setContainerAdapter(
        ContainerAdapterInterface $adapter,
    ): string {
        self::$ADAPTER  = $adapter;

        return self::class;
    }

    /**
     * factory
     *
     * @return self このインスタンス
     */
    public static function factory(): self
    {
        return new self();
    }

    /**
     * 文字列サービスを返します。
     *
     * @return StringServiceInterface 文字列サービス
     */
    public static function getStringService(): StringServiceInterface
    {
        return self::factory()->get(StringServiceInterface::class);
    }

    /**
     * 値オブジェクトキャッシュサービスを返します。
     *
     * @return ValueObjectCacheServiceInterface 値オブジェクトキャッシュサービス
     */
    public static function getValueObjectCacheService(): ValueObjectCacheServiceInterface
    {
        return self::factory()->get(ValueObjectCacheServiceInterface::class);
    }

    /**
     * デフォルトのコンテナアダプタを構築して返します。
     *
     * @return ContainerAdapterInterface デフォルトのコンテナアダプタ
     */
    private static function createDefaultContainerAdapter(): ContainerAdapterInterface
    {
        return new ContainerAdapter(
            container           : new Container(),
            containerAccessor   : new ContainerAccessor(),
            default_settings    : self::getDefaultSettings(),
        );
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        self::$ADAPTER ?? self::setContainerAdapter(self::createDefaultContainerAdapter());
    }

    /**
     * オブジェクトを設定します。
     *
     * @param  string        $id          ID
     * @param  string|object $object      オブジェクト
     * @param  bool          $shared      共有するかどうか
     * @param  bool          $only_create 逐次生成に限定するかどうか
     * @param  null|array    $parameters  コンストラクト時引数
     * @return self          このインスタンス
     */
    public function set(string $id, string|object $object, bool $shared = false, bool $only_create = false, array $parameters = []): self
    {
        self::$ADAPTER->set($id, $object, $shared, $only_create, $parameters);

        return $this;
    }

    /**
     * IDに紐づくオブジェクトがあるかどうかを返します
     *
     * @param  string $id ID
     * @return bool   IDに紐づくオブジェクトがあるかどうか
     */
    public function has(string $id): bool
    {
        return self::$ADAPTER->has($id);
    }

    /**
     * オブジェクトを取得します。
     *
     * @param  string $id ID
     * @return object オブジェクト
     */
    public function get(string $id): object
    {
        return self::$ADAPTER->get($id);
    }

    /**
     * オブジェクトを構築し返します。
     *
     * @param  string $id            ID
     * @param  mixed  ...$parameters 構築時引数
     * @return object オブジェクト
     */
    public function create(string $id, mixed ...$parameters): object
    {
        return self::$ADAPTER->create($id, ...$parameters);
    }
}
