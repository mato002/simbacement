<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobListing extends Model
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'title',
        'slug',
        'location',
        'department',
        'employment_type',
        'summary',
        'requirements',
        'responsibilities',
        'is_active',
        'closes_at',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'closes_at' => 'date',
            'published_at' => 'datetime',
        ];
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function (Builder $builder) {
                $builder->whereNull('closes_at')->orWhere('closes_at', '>=', now()->toDateString());
            })
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
