<?php

namespace Qualp\Api\Exceptions\V3;

use Qualp\Api\Exceptions\QualpApiException;

class InvalidPolylineException extends QualpApiException
{
    public static function invalidPolylinePrecision()
    {
        return new static('A precisão de polilinha informada não é válida. Por favor, utilize precisão 5 ou 6.');
    }
}