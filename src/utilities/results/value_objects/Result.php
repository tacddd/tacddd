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

namespace tacddd\utilities\results\value_objects;

use tacddd\collections\traits\results\result_details\ResultDetailsCollectionInterface;
use tacddd\utilities\containers\ContainerService;
use tacddd\utilities\status\StatusFactoryService;
use tacddd\utilities\status\value_objects\Outcome;
use tacddd\value_objects\results\result\traits\ResultInterface;
use tacddd\value_objects\results\result\traits\ResultTrait;
use tacddd\value_objects\results\result_details\traits\ResultDetailsInterface;

/**
 * オブジェクト
 */
final class Result implements ResultInterface
{
    use ResultTrait;

    /**
     * factory
     *
     * @param  string|int|float|bool|Outcome 結果が成功しているかどうか
     * @param  null|string                           $message メッセージ
     * @param  mixed                                 $result  処理結果
     * @param  null|ResultDetailsCollectionInterface $details 詳細情報
     * @return ResultInterface                       結果
     */
    public static function of(
        string|int|float|bool|Outcome $outcome,
        null|string $message,
        mixed $result,
        null|array|ResultDetailsInterface|ResultDetailsCollectionInterface $details,
    ): ResultInterface {
        return new self(
            StatusFactoryService::createOutcome($outcome)->value,
            $message,
            $result,
            \is_array($details) || $details instanceof ResultDetailsInterface ? ContainerService::factory()->create(ResultDetailsCollectionInterface::class, $details) : $details,
        );
    }
}
