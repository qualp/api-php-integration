<?php

namespace Qualp\Api\Exceptions\V4;

use Qualp\Api\Exceptions\QualpApiException;

class InvalidPolylineException extends QualpApiException
{
    public static function invalidPolylinePrecision()
    {
        return new static('A precisão da polilinha deve ser 5 ou 6.');
    }

    public static function missingPolylineString()
    {
        return new static('Você precisa enviar a polilinha');
    }

    public static function missingPolylinePrecision()
    {
        return new static('Você precisa informar a precisão da polilinha');
    }

    public static function invalidPolylineFormat()
    {
        return new static('Você precisa informar a polilinha no formato `string`');
    }
}
