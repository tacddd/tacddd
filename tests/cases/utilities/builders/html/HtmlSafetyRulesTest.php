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
use tacddd\utilities\builders\html\safety\builders\HtmlSafetyRulesBuilder;
use tacddd\utilities\builders\html\safety\enums\HtmlAttributeValueMatchOperatorEnum;

/**
 * @internal
 */
#[CoversClass(Html::class)]
class HtmlSafetyRulesTest extends AbstractTestCase
{
    #[Test]
    public function fromHtmlFragmentWithoutRulesIsCompatible(): void
    {
        $this->assertSame('', Html::fromHtmlFragment('')->toHtml());
        $this->assertSame('text_node', Html::fromHtmlFragment('text_node')->toHtml());
        $this->assertSame('text<br>node', Html::fromHtmlFragment('text<br>node')->toHtml());
    }

    #[Test]
    public function dropTagsFromHtmlFragment(): void
    {
        $rules = HtmlSafetyRulesBuilder::create()
            ->dropTags(['script'])
            ->build();

        $config = HtmlConfig::factory([
            'pretty_print'  => false,
            'safety_rules'  => $rules,
        ]);

        $html = '<div>before<script>alert(1)</script>after</div>';

        $this->assertSame('<div>beforeafter</div>', Html::fromHtmlFragment($html, $config)->toHtml());
    }

    #[Test]
    public function escapeTagsFromHtmlFragment(): void
    {
        $rules = HtmlSafetyRulesBuilder::create()
            ->escapeTags(['script'])
            ->build();

        $config = HtmlConfig::factory([
            'pretty_print'  => false,
            'safety_rules'  => $rules,
        ]);

        $html = '<div>before<script>alert(1)</script>after</div>';

        $this->assertSame('<div>before&lt;script&gt;alert(1)&lt;/script&gt;after</div>', Html::fromHtmlFragment($html, $config)->toHtml());
    }

    #[Test]
    public function unwrapTagsFromHtmlFragment(): void
    {
        $rules = HtmlSafetyRulesBuilder::create()
            ->unwrapTags(['a'])
            ->build();

        $config = HtmlConfig::factory([
            'pretty_print'  => false,
            'safety_rules'  => $rules,
        ]);

        $html = '<div>before<a href="https://example.com">link</a>after</div>';

        $this->assertSame('<div>beforelinkafter</div>', Html::fromHtmlFragment($html, $config)->toHtml());
    }

    #[Test]
    public function dropAttributesByNameExactAndPrefix(): void
    {
        $rules = HtmlSafetyRulesBuilder::create()
            ->dropAttributesByName([
                '*' => ['onclick', 'on*'],
            ])
            ->build();

        $config = HtmlConfig::factory([
            'pretty_print'  => false,
            'safety_rules'  => $rules,
        ]);

        $html = '<div><a href="https://example.com" onclick="x()" onmouseover="y()">link</a></div>';

        $this->assertSame('<div><a href="https://example.com">link</a></div>', Html::fromHtmlFragment($html, $config)->toHtml());
    }

    #[Test]
    public function dropTagsWhenHasAttributes(): void
    {
        $rules = HtmlSafetyRulesBuilder::create()
            ->dropTagsWhenHasAttributes([
                '*' => ['on*'],
            ])
            ->build();

        $config = HtmlConfig::factory([
            'pretty_print'  => false,
            'safety_rules'  => $rules,
        ]);

        $html = '<div>before<a href="https://example.com" onclick="x()">link</a>after</div>';

        $this->assertSame('<div>beforeafter</div>', Html::fromHtmlFragment($html, $config)->toHtml());
    }

    #[Test]
    public function escapeTagsWhenHasAttributes(): void
    {
        $rules = HtmlSafetyRulesBuilder::create()
            ->escapeTagsWhenHasAttributes([
                '*' => ['on*'],
            ])
            ->build();

        $config = HtmlConfig::factory([
            'pretty_print'  => false,
            'safety_rules'  => $rules,
        ]);

        $html = '<div>before<a href="https://example.com" onclick="x()">link</a>after</div>';

        $this->assertSame(
            '<div>before&lt;a href=&quot;https://example.com&quot; onclick=&quot;x()&quot;&gt;link&lt;/a&gt;after</div>',
            Html::fromHtmlFragment($html, $config)->toHtml(),
        );
    }

    #[Test]
    public function dropAttributesWhenValueMatchesPrefix(): void
    {
        $rules = HtmlSafetyRulesBuilder::create()
            ->dropAttributesWhenValueMatches([
                'a' => [
                    'href' => [
                        HtmlAttributeValueMatchOperatorEnum::Prefix->value => ['javascript:'],
                    ],
                ],
            ])
            ->build();

        $config = HtmlConfig::factory([
            'pretty_print'  => false,
            'safety_rules'  => $rules,
        ]);

        $html = '<div><a href="javascript:alert(1)">link</a><a href="https://example.com">ok</a></div>';

        $this->assertSame('<div><a>link</a><a href="https://example.com">ok</a></div>', Html::fromHtmlFragment($html, $config)->toHtml());
    }

    #[Test]
    public function prettyPrintWithRules(): void
    {
        $rules = HtmlSafetyRulesBuilder::create()
            ->dropTags(['script'])
            ->dropAttributesByName(['*' => ['on*']])
            ->build();

        $config = HtmlConfig::factory([
            'pretty_print'  => true,
            'safety_rules'  => $rules,
        ]);

        $html = '<html><body>text<br>node<script>alert(1)</script><a onclick="x()">a</a></body></html>';

        $this->assertSame(
            '<html>
    <body>
        text<br>
        node<a>a</a>
    </body>
</html>',
            Html::fromHtml($html, $config)->toHtml(),
        );
    }
}
