<?php

namespace Qualp\Api\Exceptions\V3;

use Qualp\Api\Exceptions\QualpApiException;

class InvalidParamsException extends QualpApiException
{
    public static function invalidAxisCount()
    {
        return new static("Você deve informar um valor de eixos entre 1 e 15.");
    }

    public static function invalidFreightTableCategory()
    {
        return new static("Você deve escolher entre as categorias `A`, `B`, `C` e `D` para a tabela frete.");
    }

    public static function invalidVehicleCategory()
    {
        return new static("Você deve escolher a categoria do veículo entre 'caminhao', 'carro', 'onibus' ou 'moto'");
    }
}