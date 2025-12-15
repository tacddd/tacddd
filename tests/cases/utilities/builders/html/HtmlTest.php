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

namespace tacddd\tests\cases\utilities\builders\html;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use tacddd\tests\utilities\AbstractTestCase;
use tacddd\utilities\builders\html\config\HtmlConfig;
use tacddd\utilities\builders\html\Html;

/**
 * @internal
 */
#[CoversClass(Html::class)]
class HtmlTest extends AbstractTestCase
{
    #[Test]
    public function html(): void
    {
        $this->assertSame('text_node', Html::textNode('text_node')->toHtml());
        $this->assertSame('text&lt;br&gt;node', Html::textNode('text<br>node')->toHtml());
    }

    #[Test]
    public function fromHtmlFragment(): void
    {
        $this->assertSame('', Html::fromHtmlFragment('')->toHtml());
        $this->assertSame('text_node', Html::fromHtmlFragment('text_node')->toHtml());
        $this->assertSame('text<br>node', Html::fromHtmlFragment('text<br>node')->toHtml());
    }

    #[Test]
    public function fromHtml(): void
    {
        $notPrettyPrintHtmlConfig = HtmlConfig::factory([
            'pretty_print'  => false,
        ]);

        $prettyPrintHtmlConfig = HtmlConfig::factory([
            'pretty_print'  => true,
        ]);

        $this->assertSame('<html><body>text<br>node</body></html>', Html::fromHtml('<html><body>text<br>node</body></html>', $notPrettyPrintHtmlConfig)->toHtml());
        $this->assertSame('<html>
    <body>
        text<br>
        node
    </body>
</html>', Html::fromHtml('<html><body>text<br>node</body></html>', $prettyPrintHtmlConfig)->toHtml());
    }
}
