<?php

namespace Lendable\Interview\Dto;

use Lendable\Interview\Model\Term;

readonly class FeeCalculationContextDto
{
    /**
     * @param array<Term>
     */
    public function __construct(
        public float $amount,
        public Term $lowerTerm,
        public Term $upperTerm
    ){}
}