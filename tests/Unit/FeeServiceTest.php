<?php

namespace Lendable\Interview\Unit;

use Lendable\Interview\Exception\MissingDataException;
use Lendable\Interview\Model\Term;
use Lendable\Interview\Repository\TermRepositoryInterface;
use Lendable\Interview\Service\FeeService;
use Lendable\Interview\ValueObject\InputContext;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class FeeServiceTest extends TestCase
{
    #[Test]
    #[DataProvider('validDataProvider')]
    public function validData(string $amount, string $term, array $termsArray, string $expected)
    {
        $repoMock = $this->createMock(TermRepositoryInterface::class);
        $repoMock
            ->expects($this->once())
            ->method('findLowerAndUpperTermsByTermAndAmount')
            ->with((int)$term, (float)$amount)
            ->willReturn($termsArray);

        $inputContext = new InputContext($amount, $term);
        $service = new FeeService($repoMock);
        $actual = $service->getFee($inputContext);
        $actual = number_format($actual->fee, 2);

        $this->assertEquals($expected, $actual);
    }

    #[Test]
    public function missingTerms()
    {
        $repoMock = $this->createMock(TermRepositoryInterface::class);
        $repoMock
            ->expects($this->once())
            ->method('findLowerAndUpperTermsByTermAndAmount')
            ->with(12, 2000.00)
            ->willReturn([]);

        $inputContext = new InputContext(2000, 12);
        $service = new FeeService($repoMock);

        $this->expectException(MissingDataException::class);
        $service->getFee($inputContext);
    }

    public static function validDataProvider(): array
    {
        return [
            [
                "1000", "12",
                [
                    self::generateTerm(["amount" => 1000, "fee" => 50, "term" => 12]),
                    self::generateTerm(["amount" => 2000, "fee" => 90, "term" => 12]),
                ],
                "50.00"
            ],
            [
                "20000", "12",
                [
                    self::generateTerm(["amount" => 19000, "fee" => 380, "term" => 12]),
                    self::generateTerm(["amount" => 20000, "fee" => 400, "term" => 12]),
                ],
                "400.00"
            ],
            [
                "1000.00", "12",
                [
                    self::generateTerm(["amount" => 1000, "fee" => 50, "term" => 12]),
                    self::generateTerm(["amount" => 2000, "fee" => 90, "term" => 12]),
                ],
                "50.00"
            ],
            [
                "20000.00", "12",
                [
                    self::generateTerm(["amount" => 19000, "fee" => 380, "term" => 12]),
                    self::generateTerm(["amount" => 20000, "fee" => 400, "term" => 12]),
                ],
                "400.00"
            ],

            [
                "3456.12", "12",
                [
                    self::generateTerm(["amount" => 2000, "fee" => 90, "term" => 12]),
                    self::generateTerm(["amount" => 3000, "fee" => 90, "term" => 12]),
                ],
                "93.88"
            ],
            [
                "18961.99", "12",
                [
                    self::generateTerm(["amount" => 18000, "fee" => 360, "term" => 12]),
                    self::generateTerm(["amount" => 19000, "fee" => 380, "term" => 12]),
                ],
                "383.01"
            ],

            [
                "1000", "24",
                [
                    self::generateTerm(["amount" => 1000, "fee" => 70, "term" => 24]),
                    self::generateTerm(["amount" => 2000, "fee" => 100, "term" => 24]),
                ],
                "70.00"
            ],
            [
                "20000", "24",
                [
                    self::generateTerm(["amount" => 19000, "fee" => 760, "term" => 24]),
                    self::generateTerm(["amount" => 20000, "fee" => 800, "term" => 24]),
                ],
                "800.00"
            ],
            [
                "1000.00", "24",
                [
                    self::generateTerm(["amount" => 1000, "fee" => 70, "term" => 24]),
                    self::generateTerm(["amount" => 2000, "fee" => 100, "term" => 24]),
                ],
                "70.00"
            ],
            [
                "20000.00", "24",
                [
                    self::generateTerm(["amount" => 19000, "fee" => 760, "term" => 24]),
                    self::generateTerm(["amount" => 20000, "fee" => 800, "term" => 24]),
                ],
                "800.00"
            ],

            [
                "3456.12", "24",
                [
                    self::generateTerm(["amount" => 3000, "fee" => 120, "term" => 24]),
                    self::generateTerm(["amount" => 4000, "fee" => 160, "term" => 24]),
                ],
                "138.88"
            ],
            [
                "18961.99", "24",
                [
                    self::generateTerm(["amount" => 18000, "fee" => 720, "term" => 24]),
                    self::generateTerm(["amount" => 19000, "fee" => 760, "term" => 24]),
                ],
                "763.01"
            ],

            [
                "18961.99", "24",
                [
                    self::generateTerm(["amount" => 18000, "fee" => 850.77, "term" => 24]),
                    self::generateTerm(["amount" => 19000, "fee" => 810.50, "term" => 24]),
                ],
                "813.01"
            ],
        ];
    }

    private static function generateTerm(array $data): Term
    {
        return (new Term())->hydrate($data);
    }
}