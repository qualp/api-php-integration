<?php

namespace Qualp\Api\Exceptions\V4;

use Qualp\Api\Exceptions\QualpApiException;

class InvalidAxisException extends QualpApiException
{
    public static function invalidAxisCount()
    {
        return new static("Você precisa informar uma quantidade de eixos entre 2 e 10.");
    }
}