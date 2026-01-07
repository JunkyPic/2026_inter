<?php

namespace App\Interview\Policy\Fee;

use App\Interview\Dto\FeeCalculationContextDto;

readonly class LinearInterpolationFeePolicy implements FeePolicyInterface
{
    public function calculateFee(FeeCalculationContextDto $context): float
    {
        $fee = $context->lowerTerm->fee + ($context->amount - $context->lowerTerm->amount) *
            (
                ($context->upperTerm->fee - $context->lowerTerm->fee) / ($context->upperTerm->amount - $context->lowerTerm->amount)
            );

        $fee = round($fee, 2, PHP_ROUND_HALF_DOWN);
        $remainder = fmod($context->amount + $fee, 5);
        if ($remainder > 0) {
            $fee += (5 - $remainder);
        }

        return $fee;
    }
}