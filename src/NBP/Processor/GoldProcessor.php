<?php

namespace App\NBP\Processor;

use App\NBP\Fetcher\GoldFetcher;
use App\NBP\Service\Calculator;

class GoldProcessor
{
    public function __construct(
        private readonly GoldFetcher $fetcher,
        private readonly Calculator $calculator,
    ) {
    }

    public function processAverageGoldCost(\DateTime $from, \DateTime $to): array
    {
        $costs   = array_map(fn ($date) => $date['cena'], $this->fetcher->getGoldFromTo($from, $to));
        $average = $this->calculator->calcAverageFromArray($costs);

        $timezone = new \DateTimeZone('Europe/Warsaw');
        $from->setTimezone($timezone);
        $to->setTimezone($timezone);

        return [
            "from" => $from->format('Y-m-d\TH:i:sP'),
            "to"   => $to->format('Y-m-d\TH:i:sP'),
            "avg"  => $average,
        ];
    }
}