<?php

namespace Lendable\Interview\Policy\Fee;

use Lendable\Interview\Dto\FeeCalculationContextDto;

readonly class LinearInterpolationFeePolicy implements FeePolicyInterface
{
    public function calculateFee(FeeCalculationContextDto $context): float
    {
        /**
         * This could potentially cause some floating point math shenanigans
         * if necessary I could use BC Math for this, I wanted to avoid adding
         * any extra extensions that may not exist by default
         */
        $fee = $context->lowerTerm->fee + ($context->amount - $context->lowerTerm->amount) *
            (
                ($context->upperTerm->fee - $context->lowerTerm->fee) / ($context->upperTerm->amount - $context->lowerTerm->amount)
            );

        // TODO Please red this as it pertains to the requirements
        // The fee should be rounded up such that the sum of the fee and the loan amount is exactly divisible by 5.
        // You can assume values will always be within this range but there may be any values up to 2 decimal places

        // These requirements cannot exist together
        // let's take a real example: php bin/calculate-fee "7236.75" 24
        // amount -> 7236.75
        // fee -> 289.47
        // lets assume we round up the fee so that the sum is divisible by 5, this would result in 294
        // but regardless of the rounding the end result will
        // never be divisible by 5 since the sum of the fee and loan will always be a float with the
        // mantissa having an above zero value (in our case .75 as the sum is 7530.75)
        // in the strict sense of the requirements (sum of the fee and the loan amount is exactly divisible by 5)
        // this will always result in an incorrect sum

        // my current implementation will have the fee as a float in order to satisfy the requirement of
        // "sum of the fee and the loan amount is exactly divisible by 5"
        $fee = round($fee, 2, PHP_ROUND_HALF_DOWN);
        $remainder = fmod($context->amount + $fee, 5);
        if ($remainder > 0) {
            $fee += (5 - $remainder);
        }

        return $fee;
    }
}