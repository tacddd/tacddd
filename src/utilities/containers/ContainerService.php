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

use tacddd\utilities\caching\interfaces\ValueObjectCacheServiceInterface;
use tacddd\utilities\caching\ValueObjectCacheService;
use tacddd\utilities\containers\models\Container;
use tacddd\utilities\containers\value_objects\ContainerAccessor;
use tacddd\utilities\containers\value_objects\ContainerAdapter;
use tacddd\utilities\containers\value_objects\traits\adapters\ContainerAdapterInterface;
use tacddd\utilities\converters\interfaces\StringServiceInterface;
use tacddd\utilities\converters\StringService;

/**
 * 簡易コンテナサービス
 */
final class ContainerService
{
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
        yield ['id' => ValueObjectCacheServiceInterface::class, 'object' => ValueObjectCacheService::class, 'shared' => true];

        yield ['id' => StringServiceInterface::class, 'object' => StringService::class, 'shared' => true];
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
    public static function create(): self
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
        return self::create()->get(StringServiceInterface::class);
    }

    /**
     * 値オブジェクトキャッシュサービスを返します。
     *
     * @return ValueObjectCacheServiceInterface 値オブジェクトキャッシュサービス
     */
    public static function getValueObjectCacheService(): ValueObjectCacheServiceInterface
    {
        return self::create()->get(ValueObjectCacheServiceInterface::class);
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
     * @param  string        $id         ID
     * @param  string|object $object     オブジェクト
     * @param  bool          $shared     共有するかどうか
     * @param  null|array    $parameters コンストラクト時引数
     * @return self          このインスタンス
     */
    public function set(string $id, string|object $object, bool $shared = false, array $parameters = []): self
    {
        self::$ADAPTER->set($id, $object, $shared, $parameters);

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
}
