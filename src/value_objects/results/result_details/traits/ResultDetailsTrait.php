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

/**
 * 結果詳細特性
 */
trait ResultDetailsTrait
{
    /**
     * @var string メッセージ
     */
    public readonly ?string $message;

    /**
     * @var mixed 結果詳細
     */
    public readonly mixed $details;

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
     * 結果詳細を返します。
     *
     * @return mixed 結果詳細
     */
    public function getDetails(): mixed
    {
        return $this->details;
    }
}