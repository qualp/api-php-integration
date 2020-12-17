<?php

namespace Qualp\Api\Exceptions\V4;

use Qualp\Api\Exceptions\QualpApiException;

class InvalidParamsException extends QualpApiException
{
    public static function invalidLatLngArray() : self
    {
        return new static("Se você enviar um endereço com latitude e longitude, tenha certeza de enviar um array contendo as chaves `lat` e `lng`");
    }
}