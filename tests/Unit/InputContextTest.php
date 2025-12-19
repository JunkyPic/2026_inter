<?php

namespace Lendable\Interview\Unit;

use Lendable\Interview\Exception\InvalidAmountException;
use Lendable\Interview\Exception\InvalidTermException;
use Lendable\Interview\ValueObject\InputContext;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class InputContextTest extends TestCase
{

    #[Test]
    #[DataProvider('invalidInputDataProvider')]
    public function invalidInputContextAmount(string $amount, string $term)
    {
        $this->expectException(InvalidAmountException::class);
        new InputContext($amount, $term);
    }

    #[Test]
    #[DataProvider('invalidTermDataProvider')]
    public function invalidInputContextTerm(string $amount, string $term)
    {
        $this->expectException(InvalidTermException::class);
        new InputContext($amount, $term);
    }

    #[Test]
    #[DataProvider('validInputContextDataProvider')]
    public function validInputContextData(string $amount, string $term)
    {
        $actual = new InputContext($amount, $term);
        $this->assertEquals($actual->amount, (float)$amount);
        $this->assertEquals($actual->term, (int)$term);
    }

    public static function validInputContextDataProvider(): array {
        return [
            ["1000", "12"],
            ["20000", "12"],
            ["12345", "12"],
            ["1000", "24"],
            ["20000", "24"],
            ["12345", "24"],
        ];
    }

    public static function invalidTermDataProvider(): array {
        return [
            ["2000", "11"],
            ["2000", "25"],
            ["2000", "-100"],
            ["2000", "aaa"],
        ];
    }

    public static function invalidInputDataProvider(): array {
        return [
            ["a20,000.00", "12"],
            ["totally not a number", "12"],
            ["1", "12"],
            ["9999999", "12"],
            ["-1000", "12"],
        ];
    }
}