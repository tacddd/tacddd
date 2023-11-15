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

namespace tacddd\value_objects\bools\traits;

use tacddd\utilities\containers\ContainerService;
use tacddd\value_objects\utilities\validation\traits\ValidationResultInterface;
use tacddd\value_objects\utilities\validation\ValidationResult;
use tacddd\value_objects\utilities\validation\ValidationResultFailure;
use tacddd\value_objects\utilities\validation\ValidationResultSuccess;

/**
 * ファクトリ向け検証特性：真偽値
 */
trait BoolVerificationOfTrait
{
    /**
     * 値がこのオブジェクトで使えるかどうかを返します。
     *
     * @return self|string|int|float|bool $value 値
     * @return bool                       値がこのオブジェクトで使えるかどうか
     */
    public static function verifyOf(self|string|int|float|bool $value): bool
    {
        return $value instanceof self && \is_bool(\filter_var($value, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE));
    }

    /**
     * 値がこのオブジェクトでつかるかどうかを検証します。
     *
     * @param  self|string|int|float|bool $value 値
     * @return ValidationResult           検証結果
     */
    public static function validateOf(self|string|int|float|bool $value): ValidationResultInterface
    {
        return static::verifyOf($value) ? new ValidationResultSuccess() : ValidationResultFailure::of(
            static::getName(),
            ContainerService::getStringService()->buildDebugMessage('真偽値として利用できない値が指定されました。', $value),
        );
    }

    /**
     * 値がこのオブジェクトで使える事を保証します。
     *
     * @param self|string|int|float|bool $value 値
     */
    public static function ensureOf(self|string|int|float|bool $value): void
    {
        if (!($result = static::validateOf($value))->isSuccess()) {
            /** @var ValidationResultFailure $result */
            throw new \TypeError($result->message->value);
        }
    }
}
