<?php

namespace Qualp\Api\Support\FreightTable;

class Category
{
    private const A = 'A';
    private const B = 'B';
    private const C = 'C';
    private const D = 'D';
    private const ALL = 'all';

    public string $category = '';

    public function __construct(string $category)
    {
        $this->category = $category;
    }

    public static function A()
    {
        return new static(self::A);
    }

    public static function B()
    {
        return new static(self::B);
    }

    public static function C()
    {
        return new static(self::C);
    }

    public static function D()
    {
        return new static(self::D);
    }

    public static function ALL()
    {
        return new static(self::ALL);
    }
}
