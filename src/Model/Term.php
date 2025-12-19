<?php

namespace Lendable\Interview\Model;

class Term
{
    public ?float $amount;
    public ?float $fee;
    public ?int $term;

    public function hydrate(array $data): Term
    {
        if (isset($data['amount'])) {
            $this->amount = floatval($data['amount']);
        }
        if (isset($data['fee'])) {
            $this->fee = floatval($data['fee']);
        }
        if (isset($data['term'])) {
            $this->term = intval($data['term']);
        }

        return $this;
    }
}