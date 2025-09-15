<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'featured_image_path',
        'content',
        'is_published',
        'published_at',
    ];

    /**
     * Additional attributes that should be appended when serialising.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'featured_image_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Scope the query to only include published posts.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(function (Builder $inner) {
                $inner->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Use the slug for route model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get a publicly accessible URL for the featured image.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (! $this->featured_image_path) {
            return null;
        }

        $disk = Str::startsWith($this->featured_image_path, ['uploads/', 'uploads\\'])
            ? 'public_assets'
            : 'public';

        return Storage::disk($disk)->url($this->featured_image_path);
    }
}
