<?php

namespace App\Interview\Service;

use App\Interview\Dto\FeeCalculationContextDto;
use App\Interview\Dto\FeeDto;
use App\Interview\Exception\MissingDataException;
use App\Interview\Policy\Fee\LinearInterpolationFeePolicy;
use App\Interview\Repository\TermRepositoryInterface;
use App\Interview\ValueObject\InputContext;

readonly class FeeService
{
    public function __construct(
        private TermRepositoryInterface $termRepository
    ){}

    /**
     * @throws MissingDataException
     */
    public function getFee(InputContext $feeStructure): FeeDto
    {
        $terms = $this->termRepository->findLowerAndUpperTermsByTermAndAmount(
            $feeStructure->term,
            $feeStructure->amount
        );

        if ([] === $terms) {
            throw new MissingDataException(
                'Missing fee structure. Expected to have both an upper and a lower fee structure present.'
            );
        }

        $policy = new LinearInterpolationFeePolicy();

        $fee = $policy->calculateFee(new FeeCalculationContextDto(
            $feeStructure->amount,
            $terms[0],
            $terms[1],
        ));

        return new FeeDto($fee);
    }
}