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

namespace tacddd\collections\traits\results\result_details;

use tacddd\utilities\containers\ContainerService;
use tacddd\value_objects\results\result_details\traits\ResultDetailsInterface;

/**
 * 結果詳細コレクション特性
 */
trait ResultDetailsCollectionTrait
{
    /**
     * 受け入れ可能なクラスを返します。
     *
     * @return string|array 受け入れ可能なクラス
     */
    public static function getAllowedClass(): string
    {
        return ResultDetailsInterface::class;
    }

    /**
     * 指定されたオブジェクトからユニークキーを返します。
     *
     * @param  ResultDetailsInterface $element オブジェクト
     * @return int|string             ユニークキー
     */
    public static function createUniqueId(mixed $element): string|int
    {
        return \spl_object_id($element);
    }

    /**
     * 処理結果詳細の中に一つでも失敗があるかどうかを返します。
     *
     * @return bool 処理結果詳細の中に一つでも失敗があるかどうか
     */
    public function hasAnyFailure(): bool
    {
        foreach ($this->collection as $entity) {
            /** @var ResultDetailsInterface $entity */
            if (!$entity->getOutcome()) {
                return true;
            }
        }

        return false;
    }

    /**
     * 結果詳細を構築し追加します。
     *
     * @param  string $message メッセージ
     * @param  mixed  $details 結果詳細
     * @param  ?bool  $outcome 結果状態
     * @return static 結果詳細
     */
    public function addNew(
        string $message = '',
        mixed $details = null,
        ?bool $outcome = null,
    ): static {
        return $this->add(
            ContainerService::factory()->create(
                ResultDetailsInterface::class,
                $message,
                $details,
                $outcome,
            ),
        );
    }

    /**
     * 成功時の結果詳細を構築し追加します。
     *
     * @param  string $message メッセージ
     * @param  mixed  $details 結果詳細
     * @return static 結果詳細
     */
    public function addNewSuccess(
        string $message = '',
        mixed $details = null,
        ?bool $outcome = null,
    ): static {
        return $this->add(
            ContainerService::factory()->create(
                ResultDetailsInterface::class,
                $message,
                $details,
                true,
            ),
        );
    }

    /**
     * 失敗時の結果詳細を構築し追加します。
     *
     * @param  string $message メッセージ
     * @param  mixed  $details 結果詳細
     * @return static 結果詳細
     */
    public function addNewFailure(
        string $message = '',
        mixed $details = null,
    ): static {
        return $this->add(
            ContainerService::factory()->create(
                ResultDetailsInterface::class,
                $message,
                $details,
                false,
            ),
        );
    }
}
