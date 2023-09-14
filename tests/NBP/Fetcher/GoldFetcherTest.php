<?php

namespace App\Tests\NBP\Fetcher;

use App\NBP\Client\Client;
use App\NBP\Fetcher\GoldFetcher;
use PHPUnit\Framework\TestCase;

class GoldFetcherTest extends TestCase
{
    private const RESPONSE = [
        [
            "data" => "2013-01-02",
            "cena" => 165.83
        ],
        [
            "data" => "2013-01-03",
            "cena" => 166.97
        ],
        [
            "data" => "2013-01-04",
            "cena" => 167.43
        ],
        [
            "data" => "2013-01-07",
            "cena" => 167.98
        ],
        [
            "data" => "2013-01-08",
            "cena" => 167.26
        ],
        [
            "data" => "2013-01-09",
            "cena" => 167.48
        ],
        [
            "data" => "2013-01-10",
            "cena" => 167.98
        ],
        [
            "data" => "2013-01-11",
            "cena" => 167.59
        ],
        [
            "data" => "2013-01-14",
            "cena" => 164.61
        ],
        [
            "data" => "2013-01-15",
            "cena" => 165.18
        ],
        [
            "data" => "2013-01-16",
            "cena" => 166.14
        ],
        [
            "data" => "2013-01-17",
            "cena" => 167.58
        ],
        [
            "data" => "2013-01-18",
            "cena" => 166.14
        ],
        [
            "data" => "2013-01-21",
            "cena" => 167.89
        ],
        [
            "data" => "2013-01-22",
            "cena" => 170.11
        ],
        [
            "data" => "2013-01-23",
            "cena" => 170.34
        ],
        [
            "data" => "2013-01-24",
            "cena" => 169.51
        ],
        [
            "data" => "2013-01-25",
            "cena" => 169.23
        ],
        [
            "data" => "2013-01-28",
            "cena" => 166.44
        ],
        [
            "data" => "2013-01-29",
            "cena" => 165.50
        ],
        [
            "data" => "2013-01-30",
            "cena" => 167.01
        ],
        [
            "data" => "2013-01-31",
            "cena" => 166.85
        ]
    ];

    private Client $clientMock;
    private GoldFetcher $goldFetcher;

    protected function setUp(): void
    {
        $this->clientMock  = $this->createMock(Client::class);
        $this->goldFetcher = new GoldFetcher($this->clientMock);

        parent::setUp();
    }

    public function testGetGoldFromToSuccess(): void
    {
        $this->clientMock
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('cenyzlota/{from}/{to}'),)
            ->willReturn(self::RESPONSE);

        $fromDate = new \DateTime('2013-01-01');
        $toDate   = new \DateTime('2013-01-31');
        $result   = $this->goldFetcher->getGoldFromTo($fromDate, $toDate);

        $this->assertEquals(self::RESPONSE, $result);
    }
}
