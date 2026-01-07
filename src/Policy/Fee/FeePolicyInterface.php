<?php

namespace App\Interview\Policy\Fee;

use App\Interview\Dto\FeeCalculationContextDto;

interface FeePolicyInterface
{
    public function calculateFee(FeeCalculationContextDto $context): float;
}