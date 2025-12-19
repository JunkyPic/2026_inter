<?php

namespace Lendable\Interview\Service;

use Lendable\Interview\Dto\FeeCalculationContextDto;
use Lendable\Interview\Dto\FeeDto;
use Lendable\Interview\Exception\MissingDataException;
use Lendable\Interview\Policy\Fee\LinearInterpolationFeePolicy;
use Lendable\Interview\Repository\TermRepositoryInterface;
use Lendable\Interview\ValueObject\InputContext;

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

        // this can be moved to a factory if at any point a new type of fee calculation is needed
        // but for now keeping it simple feels like the best choice
        $policy = new LinearInterpolationFeePolicy();

        $fee = $policy->calculateFee(new FeeCalculationContextDto(
            $feeStructure->amount,
            $terms[0],
            $terms[1],
        ));

        return new FeeDto($fee);
    }
}