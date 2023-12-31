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

namespace tacddd\value_objects\figures\positive_ints\traits;

use tacddd\utilities\containers\ContainerService;
use tacddd\utilities\results\ResultFactoryService;
use tacddd\value_objects\results\result\traits\ResultInterface;
use tacddd\value_objects\utilities\validation\ValidationResult;
use tacddd\value_objects\utilities\validation\ValidationResultFailure;

/**
 * 検証特性：正の整数
 */
trait PositiveIntVerificationTrait
{
    /**
     * 値がこのオブジェクトで使えるかどうかを返します。
     *
     * @return self|int $value 値
     * @return int      値がこのオブジェクトで使えるかどうか
     */
    public static function verify(self|int $value): bool
    {
        if ($value instanceof self) {
            $value = $value->value;
        }

        if ($value < static::getMin()) {
            return false;
        }

        if ($value > static::getMax()) {
            return false;
        }

        return true;
    }

    /**
     * 値がこのオブジェクトでつかるかどうかを検証します。
     *
     * @param  self|int         $value 値
     * @return ValidationResult 検証結果
     */
    public static function validate(self|int $value): ResultInterface
    {
        if ($value instanceof self) {
            $value = $value->value;
        }

        $error_list     = [];

        if ($value < static::getMin()) {
            $error_list[]   = [
                'format'    => '{:label}には{:min}以上を入力してください。',
                'values'    => [
                    'label' => static::getName(),
                    'value' => $value,
                    'min'   => static::getMin(),
                ],
            ];

            goto failure;
        }

        if ($value > static::getMax()) {
            $error_list[]   = [
                'format'    => '{:label}には{:max}以下を入力してください。',
                'values'    => [
                    'label' => static::getName(),
                    'value' => $value,
                    'max'   => static::getMax(),
                ],
            ];

            goto failure;
        }

        if (empty($error_list)) {
            return ResultFactoryService::createSuccess();
        }

        failure:

        $resultDetailsCollection    = ResultFactoryService::createResultDetailsCollection();

        foreach ($error_list as $error) {
            $resultDetailsCollection->addNewFailure(
                ContainerService::getStringService()->buildMessage(
                    format  : $error['format'],
                    values  : $error['values'],
                ),
            );
        }

        return ResultFactoryService::createFailure(
            \sprintf('%sでエラーが発生しました。', self::getName()),
            $error_list,
            $resultDetailsCollection,
        );
    }

    /**
     * 値がこのオブジェクトで使える事を保証します。
     *
     * @param self|int $value 値
     */
    public static function ensure(self|int $value): void
    {
        if (!($result = static::validate($value))->isSuccess()) {
            /** @var ValidationResultFailure $result */
            throw new \TypeError($result->message->value);
        }
    }
}

/**
 * 検証特性：正の整数
 */
trait PositiveIntNormalizationTrait
{
    public static function verify(): int
    {
        // verification logic here
    }

    public static function validate(int $value): array
    {
    }

    public static function validateStringInput(): void
    {
    }

    public static function validateOfInput(): void
    {
    }

    public static function ensure(): void
    {
        // ensure logic here
    }

    /**
     * 値を検証します。
     *
     * @return int 値
     * @return int 検証結果
     */
    public static function normalize(int|float|string $value): int
    {
        return \filter_var((string) $value, \FILTER_VALIDATE_INT, [
            'options'   => [
                'min_range' => 1,
                'max_range' => \PHP_INT_MAX,
            ],
            'flags' => \FILTER_FLAG_ALLOW_OCTAL | \FILTER_FLAG_ALLOW_HEX | \FILTER_NULL_ON_FAILURE,
        ]) ?? throw new \TypeError(\sprintf('正の整数に利用できない値が指定されました。value:"%s"', $value));
    }
}
