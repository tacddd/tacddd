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

namespace tacddd\utilities\results;

use tacddd\collections\traits\results\result_details\ResultDetailsCollectionInterface;
use tacddd\utilities\containers\ContainerService;
use tacddd\utilities\results\interfaces\ResultFactoryServiceInterface;
use tacddd\utilities\status\value_objects\Outcome;
use tacddd\value_objects\results\result\traits\ResultInterface;
use tacddd\value_objects\results\result_details\traits\ResultDetailsInterface;

/**
 * 結果ファクトリサービス
 */
final class ResultFactoryService implements ResultFactoryServiceInterface
{
    /**
     * `結果`を構築し返します。
     *
     * @param self|string|int|float|bool|Outcome 結果が成功しているかどうか
     * @param  string                                $message メッセージ
     * @param  mixed                                 $result  処理結果
     * @param  null|ResultDetailsCollectionInterface $details 詳細情報
     * @return ResultInterface                       結果
     */
    public static function create(
        self|string|int|float|bool|Outcome $outcome,
        string $message = '',
        mixed $result = null,
        null|array|ResultDetailsCollectionInterface $details = null,
    ): ResultInterface {
        return ContainerService::factory()->create(
            ResultInterface::class,
            $outcome,
            $message,
            $result,
            $details,
        );
    }

    /**
     * 成功時の`結果`を構築し返します。
     *
     * @param  mixed                                 $result  処理結果
     * @param  string                                $message メッセージ
     * @param  null|ResultDetailsCollectionInterface $details 詳細情報
     * @return ResultInterface                       結果
     */
    public static function createSuccess(
        string $message = '',
        mixed $result = null,
        null|array|ResultDetailsCollectionInterface $details = null,
    ): ResultInterface {
        return ContainerService::factory()->create(
            ResultInterface::class,
            true,
            $message,
            $result,
            $details,
        );
    }

    /**
     * 失敗時の`結果`を構築し返します。
     *
     * @param  mixed                                 $result  処理結果
     * @param  string                                $message メッセージ
     * @param  null|ResultDetailsCollectionInterface $details 詳細情報
     * @return ResultInterface                       結果
     */
    public static function createFailure(
        string $message = '',
        mixed $result = null,
        null|array|ResultDetailsCollectionInterface $details = null,
    ): ResultInterface {
        return ContainerService::factory()->create(
            ResultInterface::class,
            false,
            $message,
            $result,
            $details,
        );
    }

    /**
     * 指定したクラスで`結果`を構築し返します。
     *
     * @param  string                                $class   クラス
     * @param  self|string|int|float|bool|Outcome    $outcome 結果が成功しているかどうか
     * @param  string                                $message メッセージ
     * @param  mixed                                 $result  処理結果
     * @param  null|ResultDetailsCollectionInterface $details 詳細情報
     * @return ResultInterface                       結果
     */
    public static function createFromClass(
        string $class,
        self|string|int|float|bool|Outcome $outcome,
        string $message = '',
        mixed $result = null,
        null|array|ResultDetailsCollectionInterface $details = null,
    ): ResultInterface {
        return new $class(
            ResultInterface::class,
            $outcome,
            $result,
            $message,
            $details,
        );
    }

    /**
     * 指定したクラスで成功時の`結果`を構築し返します。
     *
     * @param  string                                $class   クラス
     * @param  string                                $message メッセージ
     * @param  mixed                                 $result  処理結果
     * @param  null|ResultDetailsCollectionInterface $details 詳細情報
     * @return ResultInterface                       結果
     */
    public static function createSuccessFromClass(
        string $class,
        string $message = '',
        mixed $result = null,
        null|array|ResultDetailsCollectionInterface $details = null,
    ): ResultInterface {
        return new $class(
            ResultInterface::class,
            true,
            $message,
            $result,
            $details,
        );
    }

    /**
     * 指定したクラスで失敗時の`結果`を構築し返します。
     *
     * @param  string                                $class   クラス
     * @param  string                                $message メッセージ
     * @param  mixed                                 $result  処理結果
     * @param  null|ResultDetailsCollectionInterface $details 詳細情報
     * @return ResultInterface                       結果
     */
    public static function createFailureFromClass(
        string $class,
        string $message = '',
        mixed $result = null,
        null|array|ResultDetailsCollectionInterface $details = null,
    ): ResultInterface {
        return new $class(
            ResultInterface::class,
            false,
            $message,
            $result,
            $details,
        );
    }

    /**
     * 結果詳細コレクションを構築し返します。
     *
     * @return ResultDetailsCollectionInterface 結果詳細コレクション
     */
    public static function createResultDetailsCollection(): ResultDetailsCollectionInterface
    {
        return ContainerService::factory()->create(
            ResultDetailsCollectionInterface::class,
        );
    }

    /**
     * 結果詳細を構築し返します。
     *
<<<<<<< HEAD
     * @param  string                 $message メッセージ
     * @param  mixed                  $details 結果詳細
     * @param  ?bool                  $outcome 結果状態
     * @return ResultDetailsInterface 結果詳細
     */
    public static function createResultDetails(
        string $message = '',
        mixed $details = null,
        ?bool $outcome = null,
=======
     * @param  string                                $message           メッセージ
     * @param  mixed                                 $details           結果詳細
     * @param  null|ResultDetailsCollectionInterface $detailsCollection 処理結果詳細コレクション
     * @param  ?bool                                 $outcome           結果状態
     * @return ResultDetailsInterface                結果詳細
     */
    public static function createResultDetails(
        string $message = '',
        mixed $details  = null,
        ?ResultDetailsCollectionInterface $detailsCollection = null,
        ?bool $outcome  = null,
>>>>>>> master
    ): ResultDetailsInterface {
        return ContainerService::factory()->create(
            ResultDetailsInterface::class,
            $message,
            $details,
<<<<<<< HEAD
=======
            $detailsCollection,
>>>>>>> master
            $outcome,
        );
    }
}
