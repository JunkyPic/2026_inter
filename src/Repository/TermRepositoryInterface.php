<?php

namespace Lendable\Interview\Repository;

interface TermRepositoryInterface
{
    public function findLowerAndUpperTermsByTermAndAmount(int $term, float $amount);
}