<?php

namespace App\Interview\Repository;

use App\Interview\Model\Term;

class FileTermRepository implements TermRepositoryInterface
{
    /**
     * @return []|array<Term>
     */
    public function findLowerAndUpperTermsByTermAndAmount(int $term, float $amount): array
    {
        $data = require_once __DIR__ . "<FILE_HERE>";

        $filteredData = [];
        foreach ($data as $row) {
            if ($term === $row['term']) {
                $filteredData[] = $row;
            }
        }

        usort($filteredData, function($a, $b) {
            if ($a['amount'] > $b['amount']) {
                return 1;
            }

            return $a['amount'] < $b['amount'] ? -1 : 0;
        });

        $lower = null;
        $upper = null;
        foreach ($filteredData as $key => $row) {
            if ($amount >= $row['amount'] && $amount <= $filteredData[$key + 1]['amount']) {
                $lower = $row;
                $upper = $filteredData[$key + 1];
                break;
            }
        }

        if (null === $lower || null === $upper) {
            return [];
        }

        return [
            (new Term())->hydrate($lower),
            (new Term())->hydrate($upper),
        ];
    }
}