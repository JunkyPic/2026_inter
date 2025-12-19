<?php

namespace Lendable\Interview\Command;

use Lendable\Interview\Exception\InvalidAmountException;
use Lendable\Interview\Exception\InvalidTermException;
use Lendable\Interview\Exception\MissingDataException;
use Lendable\Interview\Repository\FileTermRepository;
use Lendable\Interview\Service\FeeService;
use Lendable\Interview\ValueObject\InputContext;

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
            // Upon failure, the script must print any errors to stderr and exit with status code non-zero
            // not sure if you guys meant the actual language construct "exit" or just return a response and finish execution
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
        // "Upon successful execution, the script MUST print the resulting fee to stdout followed by a line feed (\n) and exit with status code zero"
        // not sure if you guys meant the actual language construct "exit" or just return a response and finish execution
        echo self::SUCCESS;
    }
}