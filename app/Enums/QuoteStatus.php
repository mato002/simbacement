<?php

namespace App\Enums;

enum QuoteStatus: string
{
    case New = 'new';
    case UnderReview = 'under_review';
    case Quoted = 'quoted';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New Request',
            self::UnderReview => 'Under Review',
            self::Quoted => 'Quoted',
            self::Accepted => 'Accepted',
            self::Rejected => 'Rejected',
            self::Expired => 'Expired',
        };
    }
}
