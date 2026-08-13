<?php

namespace App\Models;

use App\Enums\ProjectCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'title',
        'slug',
        'location',
        'client',
        'year',
        'category',
        'summary',
        'overview',
        'challenge',
        'solution',
        'featured_image_id',
        'is_featured',
        'is_published',
        'sort_order',
        'seo_title',
        'meta_description',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'category' => ProjectCategory::class,
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'featured_image_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_project');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function imageUrl(): string
    {
        if ($this->featuredImage) {
            return $this->featuredImage->url();
        }

        $key = $this->category?->value;

        return config("media.projects.{$key}.url")
            ?? config('media.placeholder.url');
    }

    public function imageAlt(): string
    {
        return $this->featuredImage?->alt ?: $this->title;
    }
}
