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

namespace tacddd\tests\utilities\resources\dummy\objects;

final class IteratorDummay implements \Iterator
{
    public const ARRAY = [1, 2, 3, 4, 5];

    private array $array = self::ARRAY;

    private int $idx = 0;

    public function current(): mixed
    {
        return $this->array[$this->idx];
    }

    public function key(): mixed
    {
        return $this->idx;
    }

    public function next(): void
    {
        ++$this->idx;
    }

    public function rewind(): void
    {
        $this->idx  = 0;
    }

    public function valid(): bool
    {
        return \array_key_exists($this->idx, $this->array);
    }
}
