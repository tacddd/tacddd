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

namespace tacddd\services\utilities\builder\html\elements\traits;

use tacddd\services\utilities\builder\html\HtmlTextNode;
use tacddd\services\utilities\builder\html\traits\Htmlable;

/**
 * 簡易的なHTML構築ビルダインターフェースです。
 */
interface HtmlElementInterface extends Htmlable
{
    /**
     * 属性を設定・取得します。
     *
     * @param  string                   $attribute_name 属性名
     * @param  null|string|array        $value          属性値
     * @return null|string|array|static 属性値またはこのインスタンス
     */
    public function attr(string $attribute_name, string|array|null $value = null): string|array|static|null;

    /**
     * コンテキストを設定します。
     *
     * 設定済みの子要素が存在する場合、全て削除の上コンテキストに置き換えられます。
     *
     * @param  string      $context コンテキスト
     * @return self|static このインスタンス
     */
    public function context(string $context): self|static;

    /**
     * コンテキストを追加します。
     *
     * @param  HtmlTextNode|string $context 子要素
     * @return self|static         このインスタンス
     */
    public function appendContext(HtmlTextNode|string $context): self|static;

    /**
     * 子要素を追加します。
     *
     * @param  Htmlable    $child_node 子要素
     * @return self|static このインスタンス
     */
    public function appendChildNode(Htmlable $child_node): self|static;

    /**
     * 子要素を設定・取得します。
     *
     * @param  array       $children 子要素
     * @return self|static このインスタンス
     */
    public function children(?array $children = null): self|static;
}
