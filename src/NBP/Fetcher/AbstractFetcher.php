<?php

namespace App\NBP\Fetcher;

use App\NBP\Client\Client;

abstract class AbstractFetcher
{
    protected Client $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }
}