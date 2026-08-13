<?php

namespace App\Models;

use App\Enums\CustomerType;
use App\Enums\QuoteStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuoteRequest extends Model
{
    public function getRouteKeyName(): string
    {
        return 'reference';
    }

    protected $fillable = [
        'reference',
        'customer_type',
        'name',
        'company',
        'phone',
        'email',
        'delivery_location',
        'preferred_delivery_date',
        'additional_requirements',
        'status',
        'assigned_to',
        'admin_notes',
        'source',
        'ip_address',
        'reviewed_at',
        'quoted_at',
    ];

    protected function casts(): array
    {
        return [
            'customer_type' => CustomerType::class,
            'status' => QuoteStatus::class,
            'preferred_delivery_date' => 'date',
            'reviewed_at' => 'datetime',
            'quoted_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public static function generateReference(): string
    {
        $prefix = 'QT-'.now()->format('Y').'-';
        $latest = static::query()
            ->where('reference', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->value('reference');

        $next = $latest
            ? ((int) substr($latest, -6)) + 1
            : 1;

        return $prefix.str_pad((string) $next, 6, '0', STR_PAD_LEFT);
    }
}
