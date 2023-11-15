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

namespace tacddd\utilities\results;

use tacddd\collections\traits\results\result_details\ResultDetailsCollectionInterface;
use tacddd\utilities\containers\ContainerService;
use tacddd\utilities\results\interfaces\ResultFactoryServiceInterface;
use tacddd\utilities\status\value_objects\Outcome;
use tacddd\value_objects\results\result\traits\ResultInterface;

/**
 * 結果ファクトリサービス
 */
final class ResultFactoryService implements ResultFactoryServiceInterface
{
    /**
     * `結果`を返します。
     *
     * @param self|string|int|float|bool|Outcome 結果が成功しているかどうか
     * @param  mixed                                 $result  処理結果
     * @param  null|string                           $message メッセージ
     * @param  null|ResultDetailsCollectionInterface $details 詳細情報
     * @return ResultInterface                       結果
     */
    public static function create(
        self|string|int|float|bool|Outcome $outcome,
        mixed $result = null,
        null|string $message = null,
        null|array|ResultDetailsCollectionInterface $details = null,
    ): ResultInterface {
        return ContainerService::factory()->create(
            ResultInterface::class,
            $outcome,
            $result,
            $message,
            $details,
        );
    }

    /**
     * `結果`を返します。
     *
     * @param  mixed                                 $result  処理結果
     * @param  null|string                           $message メッセージ
     * @param  null|ResultDetailsCollectionInterface $details 詳細情報
     * @return ResultInterface                       結果
     */
    public static function createSuccess(
        mixed $result = null,
        null|string $message = null,
        null|array|ResultDetailsCollectionInterface $details = null,
    ): ResultInterface {
        return ContainerService::factory()->create(
            ResultInterface::class,
            true,
            $result,
            $message,
            $details,
        );
    }

    /**
     * `結果`を返します。
     *
     * @param  mixed                                 $result  処理結果
     * @param  null|string                           $message メッセージ
     * @param  null|ResultDetailsCollectionInterface $details 詳細情報
     * @return ResultInterface                       結果
     */
    public static function createFailure(
        mixed $result = null,
        null|string $message = null,
        null|array|ResultDetailsCollectionInterface $details = null,
    ): ResultInterface {
        return ContainerService::factory()->create(
            ResultInterface::class,
            false,
            $result,
            $message,
            $details,
        );
    }
}
