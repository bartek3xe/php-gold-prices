<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;

class GoldControllerTest extends WebTestCase
{
    public function testGoldJanuary2021Single(): void
    {
        $client = static::createClient();
        $client->xmlHttpRequest('POST', '/api/gold', [], [], [], json_encode([
            'from' => '2021-01-21T00:00:00Z',
            'to'   => '2021-01-21T00:00:00Z'
        ]));
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('from', $response);
        $this->assertEquals('2021-01-21T01:00:00+01:00', $response['from']);
        $this->assertArrayHasKey('to', $response);
        $this->assertEquals('2021-01-21T01:00:00+01:00', $response['to']);
        $this->assertArrayHasKey('avg', $response);
        $this->assertEquals(222.66, $response['avg']);
    }

    public function testGoldJanuary2021Range(): void
    {
        $client = static::createClient();
        $client->xmlHttpRequest('POST', '/api/gold', [], [], [], json_encode([
            'from' => '2021-01-01T00:00:00Z',
            'to'   => '2021-01-31T00:00:00Z'
        ]));
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('from', $response);
        $this->assertEquals('2021-01-01T01:00:00+01:00', $response['from']);
        $this->assertArrayHasKey('to', $response);
        $this->assertEquals('2021-01-31T01:00:00+01:00', $response['to']);
        $this->assertArrayHasKey('avg', $response);
        $this->assertEquals(223.51684210526315, $response['avg']);
    }

    public function testMissingTimezone(): void
    {
        $client = static::createClient();
        $client->xmlHttpRequest('POST', '/api/gold', [
            'from' => '2001-01-04 00:00:00',
            'to'   => '2001-01-04 00:00:00'
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCaching(): void
    {
        $client = static::createClient();
        $cache  = new FilesystemAdapter('', 0, __DIR__ . '/../../var/cache/gold');

        $cache->clear();

        $client->xmlHttpRequest('POST', '/api/gold', [], [], [], json_encode([
            'from' => '2021-01-21T00:00:00Z',
            'to'   => '2021-01-21T00:00:00Z'
        ]));

        $client->xmlHttpRequest('POST', '/api/gold', [], [], [], json_encode([
            'from' => '2021-01-21T00:00:00Z',
            'to'   => '2021-01-21T00:00:00Z'
        ]));

        $cacheItem = $cache->getItem('gold_price_2021-01-21_2021-01-21');

        $this->assertTrue($cacheItem->isHit());

        $response1 = json_decode($client->getResponse()->getContent(), true);
        $response2 = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($response1, $response2);
    }
}
