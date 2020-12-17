<?php

namespace Qualp\Api;

use GuzzleHttp\Client;

class Api
{
    protected Client $client;
    protected string $url;
    protected string $accessToken;

    public function __construct(string $accessToken)
    {
        $this->client = new Client([
            'base_uri' => $this->url,
        ]);

        $this->accessToken = $accessToken;
    }

    public function buildResponse($response, string $format)
    {
        $response = $response->getBody()->getContents();

        if ($format === 'json') {
            return json_decode($response, true);
        }

        return $response;
    }
}