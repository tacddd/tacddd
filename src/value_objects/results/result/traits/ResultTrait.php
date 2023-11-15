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

namespace tacddd\value_objects\results\result\traits;

use tacddd\collections\traits\results\result_details\ResultDetailsCollectionInterface;

/**
 * 結果特性
 */
trait ResultTrait
{
    /**
     * @var bool 結果が成功しているかどうか
     */
    public readonly bool $isSuccess;

    /**
     * @var null|string メッセージ
     */
    public readonly ?string $message;

    /**
     * @var mixed 処理結果
     */
    public readonly mixed $result;

    /**
     * @var null|ResultDetailsCollectionInterface 詳細情報
     */
    public readonly ?ResultDetailsCollectionInterface $details;

    /**
     * construct
     *
     * @param bool                                  $is_success 結果が成功しているかどうか
     * @param mixed                                 $result     処理結果
     * @param null|string                           $message    メッセージ
     * @param null|ResultDetailsCollectionInterface $details    詳細情報
     */
    public function __construct(
        bool $is_success,
        mixed $result = null,
        ?string $message = null,
        ?ResultDetailsCollectionInterface $details = null,
    ) {
        $this->isSuccess    = $is_success;
        $this->result       = $result;
        $this->message      = $message;
        $this->details      = $details;
    }

    /**
     * 結果が成功しているかどうかを返します。
     *
     * @return bool 結果が成功しているかどうか
     */
    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    /**
     * 処理結果を返します。
     *
     * @return mixed 処理結果
     */
    public function getResult(): mixed
    {
        return $this->result;
    }

    /**
     * メッセージを返します。
     *
     * @return null|string メッセージ
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * 処理結果詳細を返します。
     *
     * @return null|ResultDetailsCollectionInterface 処理結果詳細
     */
    public function getDetails(): ?ResultDetailsCollectionInterface
    {
        return $this->details;
    }
}
