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

namespace tacddd\services\utilities\builder\html\traits;

use tacddd\services\utilities\builder\html\config\HtmlConfigInterface;

/**
 * 簡易的なHTML構築ビルダ特性です。
 */
trait HtmlableTrait
{
    /**
     * @var null|HtmlConfigInterface 簡易的なHTML構築ビルダ設定
     */
    protected ?HtmlConfigInterface $htmlConfig   = null;

    /**
     * 簡易的なHTML構築ビルダ設定を取得・設定します。
     *
     * @return HtmlConfigInterface|static 簡易的なHTML構築ビルダ設定またはこのインスタンス
     */
    public function htmlConfig($htmlConfig = null): HtmlConfigInterface|static
    {
        if ($htmlConfig === null && \func_num_args() === 0) {
            return $this->htmlConfig;
        }

        if (!($htmlConfig instanceof HtmlConfigInterface)) {
            throw new \Exception(ContainerService::getStringService()->buildDebugMessage('利用できない簡易的なHTML構築ビルダ設定を指定されました。escape_format:%s', $htmlConfig));
        }

        $this->htmlConfig   = $htmlConfig;

        return $this;
    }
}
