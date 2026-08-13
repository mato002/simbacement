<?php

namespace App\Enums;

enum LocationType: string
{
    case HeadOffice = 'head_office';
    case Factory = 'factory';
    case Branch = 'branch';
    case SalesOffice = 'sales_office';
    case Distributor = 'distributor';
    case Distribution = 'distribution';

    public function label(): string
    {
        return match ($this) {
            self::HeadOffice => 'Head Office',
            self::Factory => 'Factory',
            self::Branch => 'Branch',
            self::SalesOffice => 'Sales Office',
            self::Distributor => 'Distributor',
            self::Distribution => 'Distribution',
        };
    }
}
