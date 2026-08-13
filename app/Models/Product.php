<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'tagline',
        'short_description',
        'overview',
        'technical_information',
        'applications',
        'key_benefits',
        'packaging',
        'quality_standards',
        'unit',
        'is_featured',
        'is_active',
        'is_comparable',
        'sort_order',
        'seo_title',
        'meta_description',
        'meta_keywords',
        'og_image_id',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'is_comparable' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class)->orderBy('sort_order');
    }

    public function datasheets(): HasMany
    {
        return $this->hasMany(ProductDatasheet::class)->orderBy('sort_order');
    }

    public function ogImage(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'og_image_id');
    }

    public function solutions(): BelongsToMany
    {
        return $this->belongsToMany(Solution::class, 'product_solution')
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'product_project');
    }

    public function primaryImage(): ?ProductImage
    {
        return $this->images->firstWhere('is_primary', true) ?? $this->images->first();
    }

    public function imageUrl(): string
    {
        $media = $this->primaryImage()?->media;

        if ($media) {
            return $media->url();
        }

        $categorySlug = $this->category?->slug;
        $categoryMedia = config("media.categories.{$categorySlug}.url")
            ?? config('media.placeholder.url');

        return $categoryMedia;
    }

    public function imageAlt(): string
    {
        return $this->primaryImage()?->media?->alt
            ?: $this->name;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->active()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
