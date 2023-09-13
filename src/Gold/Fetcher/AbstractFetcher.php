<?php

namespace App\Gold\Fetcher;

use App\Gold\Client\Client;

abstract class AbstractFetcher
{
    protected Client $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }
}