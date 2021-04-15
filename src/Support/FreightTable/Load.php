<?php

namespace Qualp\Api\Support\FreightTable;

class Load
{
    private const GRANEL_SOLIDO = 'granel_solido';
    private const GRANEL_LIQUIDO = 'granel_liquido';
    private const FRIGORIFICADA = 'frigorificada';
    private const CONTEINEIRIZADA = 'conteineirizada';
    private const GERAL = 'geral';
    private const NEOGRANEL = 'neogranel';
    private const PERIGOSA_GRANEL_SOLIDO = 'perigosa_granel_solido';
    private const PERIGOSA_GRANEL_LIQUIDO = 'perigosa_granel_liquido';
    private const PERIGOSA_FRIGORIFICADA = 'perigosa_frigorificada';
    private const PERIGOSA_CONTEINEIRIZADA = 'perigosa_conteineirizada';
    private const PERIGOSA_GERAL = 'perigosa_geral';
    private const PERIGOSA_PRESSURIZADA = 'perigosa_pressurizada';
    private const ALL = 'all';

    public string $load = '';

    public function __construct(string $load)
    {
        $this->load = $load;
    }

    public static function GRANEL_SOLIDO()
    {
        return new static(self::GRANEL_SOLIDO);
    }

    public static function GRANEL_LUQUIDO()
    {
        return new static(self::GRANEL_LIQUIDO);
    }

    public static function FRIGORIFICADA()
    {
        return new static(self::FRIGORIFICADA);
    }

    public static function CONTEINEIRIZADA()
    {
        return new static(self::CONTEINEIRIZADA);
    }

    public static function GERAL()
    {
        return new static(self::GERAL);
    }

    public static function NEOGRANEL()
    {
        return new static(self::NEOGRANEL);
    }

    public static function PERIGOSA_GRANEL_SOLIDO()
    {
        return new static(self::PERIGOSA_GRANEL_SOLIDO);
    }

    public static function PERIGOSA_GRANEL_LIQUIDO()
    {
        return new static(self::PERIGOSA_GRANEL_LIQUIDO);
    }

    public static function PERIGOSA_FRIGORIFICADA()
    {
        return new static(self::PERIGOSA_FRIGORIFICADA);
    }

    public static function PERIGOSA_CONTEINEIRIZADA()
    {
        return new static(self::PERIGOSA_CONTEINEIRIZADA);
    }

    public static function PERIGOSA_GERAL()
    {
        return new static(self::PERIGOSA_GERAL);
    }

    public static function PERIGOSA_PRESSURIZADA()
    {
        return new static(self::PERIGOSA_PRESSURIZADA);
    }

    public static function ALL()
    {
        return new static(self::ALL);
    }
}
