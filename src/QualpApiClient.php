<?php

namespace Qualp\Api;

use Qualp\Api\Support\Vehicles;

class QualpApiClient
{
    protected string $version;
    private string $accessToken;

    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public static function make(string $accessToken) : self
    {
        return new static($accessToken);
    }

    public function v4()
    {
        return new ApiV4($this->accessToken);
    }

    public function v3()
    {
        return new ApiV3($this->accessToken);
    }
}