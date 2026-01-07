<?php

namespace App\Interview\Command;

use App\Interview\Exception\InvalidAmountException;
use App\Interview\Exception\InvalidTermException;
use App\Interview\Exception\MissingDataException;
use App\Interview\Repository\FileTermRepository;
use App\Interview\Service\FeeService;
use App\Interview\ValueObject\InputContext;

readonly class CalculateFeeCommand
{
    private const int SUCCESS = 0;
    private const int INVALID_INPUT_TERM = 1;
    private const int INVALID_INPUT_AMOUNT = 2;
    private const int INVALID_DATA_EXCEPTION = 3;

    public function __invoke(string $amount, string $term): void
    {
        try {
            $inputContext = new InputContext($amount, $term);
        } catch (InvalidTermException|InvalidAmountException $e) {
            file_put_contents('php://stderr', $e->getMessage());
            file_put_contents(
                'php://stderr',
                ($e instanceof InvalidTermException) ? self::INVALID_INPUT_TERM : self::INVALID_INPUT_AMOUNT
            );
            return;
        }

        $repository = new FileTermRepository();
        $service = new FeeService($repository);

        try {
            $feeDto = $service->getFee($inputContext);
        }  catch (MissingDataException $e) {
            file_put_contents('php://stderr', $e->getMessage());
            file_put_contents('php://stderr', self::INVALID_DATA_EXCEPTION);
            return;
        }

        echo number_format($feeDto->fee, 2) . PHP_EOL;
        echo self::SUCCESS;
    }
}