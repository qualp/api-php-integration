<?php

namespace Qualp\Api;

use Qualp\Api\Support\Vehicles;

class QualpApiClient
{
    protected string $version;
    private string $accessToken;

    /**
     * QualpApiClient constructor.
     * @param string $accessToken
     */
    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @param string $accessToken
     * @return static
     */
    public static function withAccessToken(string $accessToken) : self
    {
        return new static($accessToken);
    }

    /**
     * @return ApiV4
     */
    public function v4()
    {
        return new ApiV4($this->accessToken);
    }

    /**
     * @return ApiV3
     */
    public function v3()
    {
        return new ApiV3($this->accessToken);
    }
}