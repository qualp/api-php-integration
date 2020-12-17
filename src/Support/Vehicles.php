<?php

namespace Qualp\Api\Support;

class Vehicles
{
    private const TRUCK = 'truck';
    private const CAR = 'car';
    private const MOTORCYCLE = 'moto';
    private const BUS = 'bus';

    public string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function TRUCK() : self
    {
        return new static(self::TRUCK);
    }

    public static function CAR() : self
    {
        return new static(self::CAR);
    }

    public static function MOTORCYCLE() : self
    {
        return new static(self::MOTORCYCLE);
    }

    public static function BUS() : self
    {
        return new static(self::BUS);
    }
}