<?php
declare (strict_types = 1);
/**
 * This file is part of lanxr/ip-locating.
 *
 * (c) lanxr <lxr4437@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Lanxr\Locating;

use GuzzleHttp\Client;
use Lanxr\Locating\Exceptions\HttpException;
use Lanxr\Locating\Exceptions\InvalidArgumentException;

class IPLocating
{
    /** @var */
    protected $key;

    /** @var array */
    protected $guzzleOptions = [];

    /**
     * IPLocating constructor.
     *
     * @param $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Get http client
     *
     * @return Client
     */
    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    /**
     * Set guzzle options
     *
     * @param array $options
     */
    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    /**
     * Get ip locating
     *
     * @param string $ip
     * @param string $format
     *
     * @return mixed|string
     *
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getIPLocating(string $ip, string $format = 'json')
    {
        $url = 'https://restapi.amap.com/v3/ip';

        if (!in_array(strtolower($format), ['json', 'xml'])) {
            throw new InvalidArgumentException('Invalid response format(json/xml): ' . $format);
        }

        $format = strtolower($format);

        $query = array_filter([
            'key' => $this->key,
            'ip' => $ip,
            'output' => $format
        ]);

        try {
            $response = $this->getHttpClient()->get($url, [
                'query' => $query,
            ])->getBody()->getContents();

            return $format === 'json' ? json_decode($response, true) : $response;
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }
}