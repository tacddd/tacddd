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

namespace tacddd\tests\utilities\specs\value_objects;

use tacddd\tests\utilities\specs\AbstractClassSpec;

/**
 * 値オブジェクトのテストで利用するスペッククラスです。
 */
abstract class AbstractValueObjectClassSpec extends AbstractClassSpec
{
    /**
     * 期待される値オブジェクトのユビキタス言語名を返します。
     *
     * @return string 期待される値オブジェクトのユビキタス言語名
     */
    abstract public function getExpectedUbiquitousLanguageName(): string;

    /**
     * 期待されるプリミティブ型を返します。
     *
     * @return string 期待されるプリミティブ型
     */
    abstract public function getPrimitiveType(): string;

    /**
     * 期待されるアジャスタ受け入れ可能型を返します。
     *
     * @return string 期待されるアジャスタ受け入れ可能型
     */
    abstract public function getAdjustParamType(): string;
}
