<?php

namespace App\Models;

use App\Enums\MessageStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'company',
        'phone',
        'email',
        'county',
        'subject',
        'message',
        'department',
        'status',
        'assigned_to',
        'admin_notes',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'status' => MessageStatus::class,
        ];
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
