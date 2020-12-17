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
}