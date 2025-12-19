<?php

namespace Lendable\Interview\Policy\Fee;

use Lendable\Interview\Dto\FeeCalculationContextDto;

interface FeePolicyInterface
{
    public function calculateFee(FeeCalculationContextDto $context): float;
}