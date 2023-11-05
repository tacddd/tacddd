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

namespace tacddd\value_objects\utilities\validation;

use tacddd\utilities\containers\ContainerService;
use tacddd\utilities\resources\strings\value_objects\Label;
use tacddd\utilities\resources\strings\value_objects\Message;
use tacddd\utilities\status\value_objects\Outcome;
use tacddd\value_objects\ValueObjectInterface;

/**
 * 入力値検証結果：失敗
 */
final class ValidationResultFailure implements
    ValueObjectInterface,
    traits\ValidationResultInterface
{
    use traits\ValidationResultTrait;

    /**
     * @var Outcome 結果
     */
    public readonly Outcome $outcome;

    /**
     * ユビキタス言語名を返します。
     *
     * @return string ユビキタス言語名
     */
    public static function getName(): string
    {
        return '入力値検証結果：失敗';
    }

    /**
     * ファクトリ
     *
     * @param  string|Label   $label   ラベル
     * @param  string|Message $message メッセージ
     * @return self           このインスタンス
     */
    public static function of(
        string|Label $label,
        string|Message $message,
    ): self {
        $ValueObjectCacheService    = ContainerService::getValueObjectCacheService();

        return new self(
            $ValueObjectCacheService->cacheableFromString(Label::class, $label),
            $ValueObjectCacheService->cacheableFromString(Message::class, $message),
        );
    }

    /**
     * Constructor
     *
     * @param Label   $label   ラベル
     * @param Message $message メッセージ
     */
    public function __construct(
        public readonly Label $label,
        public readonly Message $message,
    ) {
        $this->outcome = ContainerService::getValueObjectCacheService()->cacheableFromString(Outcome::class, 'false');
    }
}
