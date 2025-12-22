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

namespace tacddd\utilities\builders\html;

use tacddd\utilities\builders\html\config\HtmlConfigInterface;

/**
 * HTML属性です。
 */
final class HtmlAttribute
{
    /**
     * ファクトリです。
     */
    public static function factory(string $name, mixed $value, ?HtmlConfigInterface $htmlConfig): self
    {
        return new self($name, $value, $htmlConfig);
    }

    private function __construct(
        private string $name,
        private mixed $value,
        private ?HtmlConfigInterface $htmlConfig,
    ) {
    }

    /**
     * HTML属性文字列を生成します。
     */
    public function toHtml(): string
    {
        if ($this->value === null) {
            return $this->name;
        }

        return \sprintf(
            '%s="%s"',
            $this->name,
            Html::escape($this->value, $this->htmlConfig),
        );
    }
}
