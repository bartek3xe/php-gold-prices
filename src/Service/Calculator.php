<?php

namespace App\Service;

class Calculator
{
    public function calcAverageFromArray(array $array): float
    {
        if (!empty($array) && $this->hasNumericValues($array)) {
            $average = array_sum($array) / count($array);
            return (float)$average;
        }

        return 0;
    }

    private function hasNumericValues(array $array): bool
    {
        $array    = array_map(fn($item) => is_numeric($item), array_values($array));
        $filtered = array_filter($array, fn($item) => $item === false);

        if (empty($filtered)) {
            return true;
        }

        return false;
    }
}
