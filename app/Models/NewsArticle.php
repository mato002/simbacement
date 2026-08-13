<?php

namespace App\Models;

use App\Enums\NewsCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsArticle extends Model
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'body',
        'image_id',
        'author_id',
        'is_published',
        'seo_title',
        'meta_description',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'category' => NewsCategory::class,
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'image_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function imageUrl(): string
    {
        return $this->image?->url()
            ?? config('media.news.url')
            ?? config('media.placeholder.url');
    }

    public function imageAlt(): string
    {
        return $this->image?->alt ?: $this->title;
    }
}
