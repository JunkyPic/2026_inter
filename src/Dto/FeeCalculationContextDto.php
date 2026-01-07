<?php

namespace App\Interview\Dto;

use App\Interview\Model\Term;

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