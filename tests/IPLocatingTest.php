<?php
/**
 * This file is part of lanxr/ip-locating.
 *
 * (c) lanxr <lxr4437@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Lanxr\Locating\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Lanxr\Locating\Exceptions\HttpException;
use Lanxr\Locating\Exceptions\InvalidArgumentException;
use Lanxr\Locating\IPLocating;
use Mockery\Matcher\AnyArgs;
use PHPUnit\Framework\TestCase;

class IPLocatingTest extends TestCase
{
    public function testGetIPLocating()
    {
        $response = new Response(200, [], '{"success": true}');
        $client = \Mockery::mock(Client::class);
        $client->allows()->get('https://restapi.amap.com/v3/ip', [
            'query' => [
                'key' => 'mock-key',
                'ip' => '114.247.50.2',
                'output' => 'json'
            ]
        ])->andReturn($response);

        $ip = \Mockery::mock(IPLocating::class, ['mock-key'])->makePartial();
        $ip->allows()->getHttpClient()->andReturn($client);

        $this->assertSame(['success' => true], $ip->getIPLocating('114.247.50.2'));
    }

    public function testGetIPLocatingWithGuzzleRuntimeException()
    {
        $client = \Mockery::mock(Client::class);
        $client->allows()->get(new AnyArgs())->andThrow(new \Exception('request timeout'));

        $ip = \Mockery::mock(IPLocating::class, ['mock-key'])->makePartial();
        $ip->allows()->getHttpClient()->andReturn($client);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('request timeout');

        $ip->getIPLocating('114.247.50.2');
    }

    public function testGetIPLocatingWithInvalidArgumentFormat()
    {
        $ip = new IPLocating('mock-key');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid response format(json/xml): array');

        $ip->getIPLocating('114.247.50.2', 'array');

        $this->fail('Failed to assert getIPLocating throw exception with invalid argument.');
    }

    public function testGetHttpClient()
    {
        $ip = new IPLocating('mock-key');

        $this->assertInstanceOf(ClientInterface::class, $ip->getHttpClient());
    }

    public function testSetGuzzleOptions()
    {
        $ip = new IPLocating('mock-key');

        $this->assertNull($ip->getHttpClient()->getConfig('timeout'));

        $ip->setGuzzleOptions(['timeout' => 5000]);
        $this->assertSame(5000, $ip->getHttpClient()->getConfig('timeout'));
    }
}