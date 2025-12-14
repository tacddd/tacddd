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

namespace tacddd\tests\cases\utilities\converters;

use PHPUnit\Framework\Attributes\Test;
use tacddd\tests\utilities\AbstractTestCase;
use tacddd\utilities\converters\StringService;

/**
 * @internal
 */

/**
 * @internal
 */
class StringServiceTest extends AbstractTestCase
{
    #[Test]
    public function buildMessage(): void
    {
        $stringService  = new StringService();

        $this->assertSame('fuga', $stringService->buildMessage('${hoge}', ['hoge' => 'fuga']));
        $this->assertSame('piyo', $stringService->buildMessage('${hoge}', ['hoge' => '${fuga}', 'fuga' => 'piyo']));
        $this->assertSame('piyo', $stringService->buildMessage('${h${foo}e}', ['foo' => 'og', 'hoge' => '${fuga}', 'fuga' => 'piyo']));
        $this->assertSame('piyopiyo', $stringService->buildMessage('${h${foo}e}${h${foo}e}', ['foo' => 'og', 'hoge' => '${fuga}', 'fuga' => 'piyo']));
        $this->assertSame('piyo${piyo}', $stringService->buildMessage('${h${foo}e}${piyo}', ['foo' => 'og', 'hoge' => '${fuga}', 'fuga' => 'piyo']));

        $this->assertSame('fuga', $stringService->buildMessage('${hoge:fuga}', []));
    }
}
