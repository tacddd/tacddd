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

namespace tacddd\tests\utilities\specs;

/**
 * クラスオブジェクトのテストで利用するスペッククラスです。
 */
abstract class AbstractClassSpec
{
    /**
     * テスト対象のクラスパスを返します。
     *
     * @return string テスト対象の値クラスパス
     */
    abstract public function getClassPath(): string;

    /**
     * 期待されるクラス継承構造を返します。
     *
     * @return array 期待されるクラス継承構造
     */
    abstract public function getExpectedExceptExtendClasses(): array;

    /**
     * 期待される実装済みインターフェースを返します。
     *
     * @return array 期待される実装済みインターフェース
     */
    abstract public function getExpectedImplementInterfaces(): array;

    /**
     * 期待される使用済み特性を返します。
     *
     * @return array 期待される使用済み特性
     */
    abstract public function getExpectedUsingTraits(): array;
}
