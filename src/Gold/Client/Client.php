<?php

namespace App\Gold\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Utils;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private const BASE_API_URL = 'http://api.nbp.pl/api/';

    public function __construct(private readonly ClientInterface $client)
    {
    }

    private function renderUrl(string $requestPath, array $placeholders = []): string
    {
        $path = str_replace(array_keys($placeholders), array_values($placeholders), $requestPath);

        return self::BASE_API_URL . $path;
    }

    function get(string $path, array $keys, ResponseInterface &$response = null): array
    {
        $response = $this->client->request(
            'GET',
            $this->renderUrl($path, $keys),
        );

        if (($statusCode = $response->getStatusCode()) !== 200) {
            throw new \RuntimeException('Unable to get data', $statusCode);
        }

        return Utils::jsonDecode($response->getBody()->getContents(), true);
    }
}
