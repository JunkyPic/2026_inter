<?php

namespace App\Interview\Repository;

interface TermRepositoryInterface
{
    public function findLowerAndUpperTermsByTermAndAmount(int $term, float $amount);
}