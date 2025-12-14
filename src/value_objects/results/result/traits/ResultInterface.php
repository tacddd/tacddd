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

namespace tacddd\value_objects\results\result\traits;

use tacddd\collections\traits\results\result_details\ResultDetailsCollectionInterface;

/**
 * 結果インターフェース
 */
interface ResultInterface
{
    /**
     * 結果が成功しているかどうかを返します。
     *
     * @return bool 結果が成功しているかどうか
     */
    public function isSuccess(): bool;

    /**
     * 処理結果を返します。
     *
     * @return mixed 処理結果
     */
    public function getResult(): mixed;

    /**
     * メッセージを返します。
     *
     * @return string メッセージ
     */
    public function getMessage(): string;

    /**
     * 処理結果詳細コレクションを返します。
     *
     * @return null|ResultDetailsCollectionInterface 処理結果詳細コレクション
     */
    public function getDetailsCollection(): ?ResultDetailsCollectionInterface;

    /**
     * 処理結果詳細の中に一つでも失敗があるかどうかを返します。
     *
     * @return bool 処理結果詳細の中に一つでも失敗があるかどうか
     */
    public function hasAnyDetailsFailure(): bool;
}
