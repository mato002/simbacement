<?php

namespace App\Enums;

enum ProjectCategory: string
{
    case Residential = 'residential';
    case Commercial = 'commercial';
    case Infrastructure = 'infrastructure';
    case Industrial = 'industrial';

    public function label(): string
    {
        return match ($this) {
            self::Residential => 'Residential',
            self::Commercial => 'Commercial',
            self::Infrastructure => 'Infrastructure',
            self::Industrial => 'Industrial',
        };
    }
}
