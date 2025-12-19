<?php

namespace Lendable\Interview\E2E;

use Lendable\Interview\Command\CalculateFeeCommand;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CalculateFeeCommandTest extends TestCase
{
    #[Test]
    #[RunInSeparateProcess]
    #[DataProvider('validDataProvider')]
    public function invalidAmount(float $amount, string $expectedResult, int $term)
    {
        ob_start();
        (new CalculateFeeCommand())($amount, $term);
        $actualResult = ob_get_clean();

        $this->assertSame($actualResult, $expectedResult);
    }

    public static function validDataProvider(): array
    {
        return [
            [8000, '320.00'. PHP_EOL . '0', 24],
            [9000, '360.00'. PHP_EOL . '0', 24],
            [10000, '400.00'. PHP_EOL . '0', 24],

            [13000, '260.00'. PHP_EOL . '0', 12],
            [14000, '280.00'. PHP_EOL . '0', 12],
            [15000, '300.00'. PHP_EOL . '0', 12],

            [3456.12, '138.88'. PHP_EOL . '0', 24],

            [1000.01, '54.99'. PHP_EOL . '0', 12],
            [19999.99, '400.01'. PHP_EOL . '0', 12],
        ];
    }
}