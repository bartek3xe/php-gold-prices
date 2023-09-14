<?php

namespace App\Tests\NBP\Client;

use App\NBP\Client\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class ClientTest extends TestCase
{
    private ClientInterface $httpClient;
    private ResponseInterface $response;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(ClientInterface::class);
        $this->response   = $this->createMock(ResponseInterface::class);

        parent::setUp();
    }

    public function testGetSuccessForDate(): void
    {
        $responseArrayBody = ['data' => '2021-01-08', 'cena' => 226.28];
        $responseJsonBody  = json_encode($responseArrayBody);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                'http://api.nbp.pl/api/cenyzlota/2021-01-08',
            )
            ->willReturn(new Response(200, [], $responseJsonBody));

        $client = new Client($this->httpClient);
        $result = $client->get('cenyzlota/2021-01-08', [], $this->response);

        $this->assertEquals('2021-01-08', $result['data']);
        $this->assertEquals(226.28, $result['cena']);
    }

    public function testGetFailure(): void
    {
        $this->httpClient->expects($this->once())
            ->method('request')
            ->willReturn(new Response(404, [], 'Not Found'));

        $client = new Client($this->httpClient);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to get data');
        $this->expectExceptionCode(404);

        $client->get('test/{key}', ['{key}' => 'path']);
    }
}
