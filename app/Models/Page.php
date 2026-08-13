<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'title',
        'slug',
        'eyebrow',
        'headline',
        'summary',
        'hero_image_url',
        'sections',
        'is_published',
        'sort_order',
        'seo_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'sections' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function heroImageUrl(): string
    {
        if (filled($this->hero_image_url)) {
            return $this->hero_image_url;
        }

        return config("media.pages.{$this->slug}.url")
            ?? config('media.placeholder.url');
    }

    public function heroImageAlt(): string
    {
        return config("media.pages.{$this->slug}.alt")
            ?? $this->title;
    }
}
