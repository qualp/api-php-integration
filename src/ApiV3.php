<?php

namespace Qualp\Api;

class ApiV3 extends Api
{
    public function __construct(string $accessToken)
    {
        parent::__construct($accessToken);
    }
}