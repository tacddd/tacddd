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

namespace tacddd\value_objects\traits\array_access;

/**
 * ヌラブル配列型値オブジェクト特性
 */
trait NullableArrayAccessTrait
{
    /**
     * @var null|array 値
     */
    public ?array $value;

    /**
     * オフセットが存在するかどうかを返します。
     *
     * @param  mixed $offset 調べたいオフセット
     * @return bool  オフセットが存在するかどうか
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->value[$offset]);
    }

    /**
     * オフセットの値を返します。
     *
     * @param  mixed $offset 取得したいオフセット
     * @return mixed 値
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->value[$offset] ?? null;
    }

    /**
     * 指定したオフセットに値を設定します。
     *
     * @param mixed $offset 設定したいオフセット
     * @param mixed $value  値
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (null === $offset) {
            $this->value[] = $value;
        } else {
            $this->value[$offset] = $value;
        }
    }

    /**
     * オフセットの設定を解除します。
     *
     * @param mixed $offset 解除したいオフセット
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->value[$offset]);
    }

    /**
     * 外部イテレータを返します。
     *
     * @return \Traversable 外部イテレータ
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->value);
    }
}
