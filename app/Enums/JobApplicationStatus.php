<?php

namespace App\Enums;

enum JobApplicationStatus: string
{
    case Received = 'received';
    case Shortlisted = 'shortlisted';
    case Rejected = 'rejected';
    case Hired = 'hired';

    public function label(): string
    {
        return match ($this) {
            self::Received => 'Received',
            self::Shortlisted => 'Shortlisted',
            self::Rejected => 'Rejected',
            self::Hired => 'Hired',
        };
    }
}
