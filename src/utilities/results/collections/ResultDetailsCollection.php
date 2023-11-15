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

namespace tacddd\utilities\results\collections;

use tacddd\collections\objects\traits\magical_accesser\ObjectCollectionMagicalAccessorInterface;
use tacddd\collections\objects\traits\magical_accesser\ObjectCollectionMagicalAccessorTrait;
use tacddd\collections\objects\traits\ObjectCollectionInterface;
use tacddd\collections\objects\traits\ObjectCollectionTrait;
use tacddd\collections\traits\results\result_details\ResultDetailsCollectionInterface;
use tacddd\collections\traits\results\result_details\ResultDetailsCollectionTrait;
use tacddd\utilities\containers\ContainerService;
use tacddd\value_objects\traits\results\result_details\ResultDetailsInterface;

/**
 * 結果詳細コレクション
 */
final class ResultDetailsCollection implements
    ObjectCollectionInterface,
    ObjectCollectionMagicalAccessorInterface,
    ResultDetailsCollectionInterface
{
    use ObjectCollectionTrait;
    use ObjectCollectionMagicalAccessorTrait;
    use ResultDetailsCollectionTrait;

    /**
     * factory
     *
     * @param  array|ResultDetailsInterface|ResultDetailsCollectionInterface $value 値
     * @return self                                                          このインスタンス
     */
    public static function of(
        array|ResultDetailsInterface|ResultDetailsCollectionInterface $value,
    ): self {
        if ($value instanceof ResultDetailsCollectionInterface) {
            return $value;
        }

        if ($value instanceof ResultDetailsInterface) {
            $value  = [$value];
        }

        foreach ($value as $idx => $element) {
            if (!($element instanceof ResultDetailsInterface)) {
                $value[$idx]    = ContainerService::factory()->create(ResultDetailsInterface::class, $element);
            }
        }

        return new self($value);
    }
}
