<?php

namespace App\Interview\ValueObject;

use App\Interview\Exception\InvalidAmountException;
use App\Interview\Exception\InvalidTermException;
use NumberFormatter;

readonly class InputContext
{
    public float $amount;
    public int $term;

    /**
     * @throws InvalidAmountException
     * @throws InvalidTermException
     */
    public function __construct(string $amount, string $term)
    {
        $numberFormatter = new NumberFormatter("en", NumberFormatter::DECIMAL);
        $amount = $numberFormatter->parse($amount);

        if ($numberFormatter->getErrorCode() !== 0) {
            throw new InvalidAmountException("Expected amount to be a parsable decimal number.");
        }
        if ($amount < 1000) {
            throw new InvalidAmountException("Amount must be higher than or equal to 1000");
        }
        if ($amount > 20000) {
            throw new InvalidAmountException("Amount must be lower than or equal to 20000");
        }

        $term = intval($term);
        if ($term !== 12 && $term !== 24) {
            throw new InvalidTermException("Term must be a either 12 or 24");
        }

        $this->amount = $amount;
        $this->term = $term;
    }
}