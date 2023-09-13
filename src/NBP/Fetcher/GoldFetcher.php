<?php

namespace App\NBP\Fetcher;

class GoldFetcher extends AbstractFetcher
{
    private const GOLD = 'cenyzlota/{from}/{to}';

    public function getGoldFromTo(\DateTime $from, \DateTime $to): array
    {
        return $this->client->get(
            self::GOLD, [
                '{from}' => $from->format('Y-m-d'),
                '{to}'   => $to->format('Y-m-d'),
            ]
        );
    }
}
