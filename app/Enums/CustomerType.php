<?php

namespace App\Enums;

enum CustomerType: string
{
    case Individual = 'individual';
    case Contractor = 'contractor';
    case Developer = 'developer';
    case Hardware = 'hardware';
    case Distributor = 'distributor';
    case Company = 'company';

    public function label(): string
    {
        return match ($this) {
            self::Individual => 'Individual',
            self::Contractor => 'Contractor',
            self::Developer => 'Developer',
            self::Hardware => 'Hardware',
            self::Distributor => 'Distributor',
            self::Company => 'Company',
        };
    }
}
