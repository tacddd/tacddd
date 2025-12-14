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

namespace tacddd\tests\cases\utilities\results;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use tacddd\collections\traits\results\result_details\ResultDetailsCollectionInterface;
use tacddd\tests\utilities\AbstractTestCase;
use tacddd\utilities\results\ResultFactoryService;
use tacddd\value_objects\results\result\traits\ResultInterface;
use tacddd\value_objects\results\result_details\traits\ResultDetailsInterface;

/**
 * @internal
 */
#[CoversClass(ResultFactoryService::class)]
class ResultFactoryServiceTest extends AbstractTestCase
{
    public static function resultDataProvider(): iterable
    {
        foreach (static::successResultDataProvider() as $data) {
            yield $data;
        }

        foreach (static::failureResultDataProvider() as $data) {
            yield $data;
        }
    }

    public static function successResultDataProvider(): iterable
    {
        yield [true, null, '', null];

        yield [true, [], 'message', null];

        yield [true, null, 'message', null];
    }

    public static function failureResultDataProvider(): iterable
    {
        yield [false, null, '', null];

        yield [false, [], '', null];

        yield [false, null, 'message', null];
    }

    #[Test]
    #[DataProvider('resultDataProvider')]
    public function resultFactory(bool $is_success, mixed $result, string $message, null|array|ResultDetailsInterface|ResultDetailsCollectionInterface $details): void
    {
        $actual = ResultFactoryService::create($is_success, $message, $result, $details);

        $this->assertInstanceOf(ResultInterface::class, $actual);
        $this->assertSame($is_success, $actual->isSuccess());
        $this->assertSame($result, $actual->getResult());
        $this->assertSame($message, $actual->getMessage());
        $this->assertSame($details, $actual->getDetailsCollection());
    }

    #[Test]
    #[DataProvider('successResultDataProvider')]
    public function createSuccess(bool $is_success, mixed $result, string $message, null|array|ResultDetailsInterface|ResultDetailsCollectionInterface $details): void
    {
        $actual = ResultFactoryService::createSuccess($message, $result, $details);

        $this->assertInstanceOf(ResultInterface::class, $actual);
        $this->assertTrue($actual->isSuccess());
        $this->assertSame($result, $actual->getResult());
        $this->assertSame($message, $actual->getMessage());
        $this->assertSame($details, $actual->getDetailsCollection());
    }

    #[Test]
    #[DataProvider('failureResultDataProvider')]
    public function createFailure(bool $is_success, mixed $result, string $message, null|array|ResultDetailsInterface|ResultDetailsCollectionInterface $details): void
    {
        $actual = ResultFactoryService::createFailure($message, $result, $details);

        $this->assertInstanceOf(ResultInterface::class, $actual);
        $this->assertFalse($actual->isSuccess());
        $this->assertSame($result, $actual->getResult());
        $this->assertSame($message, $actual->getMessage());
        $this->assertSame($details, $actual->getDetailsCollection());
    }

    #[Test]
    public function createResultDetails(): void
    {
        $message            = '';
        $details            = null;
        $detailsCollection  = null;
        $outcome            = null;

        $actual     = ResultFactoryService::createResultDetails($message, $details, $detailsCollection, $outcome);

        $this->assertInstanceOf(ResultDetailsInterface::class, $actual);
        $this->assertSame($message, $actual->getMessage());
        $this->assertSame($details, $actual->getDetails());
        $this->assertSame($detailsCollection, $actual->getDetailsCollection());
        $this->assertNull($actual->getOutcome());

        // ==============================================
        $message            = 'a';
        $details            = 1;
        $detailsCollection  = ResultFactoryService::createResultDetailsCollection();
        $outcome            = true;

        $actual     = ResultFactoryService::createResultDetails($message, $details, $detailsCollection, $outcome);

        $this->assertInstanceOf(ResultDetailsInterface::class, $actual);
        $this->assertSame($message, $actual->getMessage());
        $this->assertSame($details, $actual->getDetails());
        $this->assertSame($detailsCollection, $actual->getDetailsCollection());
        $this->assertTrue($actual->getOutcome());

        // ==============================================
        $message            = 'b';
        $details            = true;
        $detailsCollection  = ResultFactoryService::createResultDetailsCollection();
        $outcome            = false;

        $actual     = ResultFactoryService::createResultDetails($message, $details, $detailsCollection, $outcome);

        $this->assertInstanceOf(ResultDetailsInterface::class, $actual);
        $this->assertSame($message, $actual->getMessage());
        $this->assertSame($details, $actual->getDetails());
        $this->assertSame($detailsCollection, $actual->getDetailsCollection());
        $this->assertFalse($actual->getOutcome());
    }
}
