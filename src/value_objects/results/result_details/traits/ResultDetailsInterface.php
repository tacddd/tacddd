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

namespace tacddd\value_objects\results\result_details\traits;

use tacddd\collections\traits\results\result_details\ResultDetailsCollectionInterface;

/**
 * 結果詳細インターフェース
 */
interface ResultDetailsInterface
{
    /**
     * メッセージを返します。
     *
     * @return string メッセージ
     */
    public function getMessage(): string;

    /**
     * 結果詳細を返します。
     *
     * @return mixed 結果詳細
     */
    public function getDetails(): mixed;

    /**
     * 処理結果詳細コレクションを返します。
     *
     * @return null|ResultDetailsCollectionInterface 処理結果詳細コレクション
     */
    public function getDetailsCollection(): ?ResultDetailsCollectionInterface;

    /**
     * 結果状態を返します。
     *
     * @return bool 結果状態
     */
    public function getOutcome(): ?bool;
}
