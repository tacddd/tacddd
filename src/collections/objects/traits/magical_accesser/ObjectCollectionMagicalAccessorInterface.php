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

namespace tacddd\collections\objects\traits\magical_accesser;

/**
 * オブジェクトコレクションマジックアクセス特性
 */
interface ObjectCollectionMagicalAccessorInterface
{
    /**
     * @var array アクション設定
     */
    public const ACTION_SPEC_MAP = [
        'findOneToMapBy'    => ['length' => 14, 'use_args' => true,  'separator' => 'In'],
        'findToMapBy'       => ['length' => 11, 'use_args' => true,  'separator' => 'In'],
        'toOneMapIn'        => ['length' => 10, 'use_args' => false, 'separator' => 'In'],
        'findOneBy'         => ['length' => 9,  'use_args' => true,  'separator' => 'And'],
        'removeBy'          => ['length' => 8,  'use_args' => true,  'separator' => 'And'],
        'toMapIn'           => ['length' => 7,  'use_args' => false, 'separator' => 'In'],
        'findBy'            => ['length' => 6,  'use_args' => true,  'separator' => 'And'],
        'hasBy'             => ['length' => 5,  'use_args' => true,  'separator' => 'And'],
    ];

    /**
     * マップキーをパースして返します。
     *
     * @param  string $find_key  マップキー
     * @param  string $separator セパレータ
     * @return array  キー配列
     */
    public static function parseFindKey(string $find_key, string $separator): array;

    /**
     * Magical method
     *
     * @param  string $method_name メソッド名
     * @param  array  $arguments   引数
     * @return mixed  返り値
     */
    public function __call(string $method_name, array $arguments): mixed;
}
