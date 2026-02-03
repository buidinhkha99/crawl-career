<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

class Post extends Model
{
    use HasFactory, HasTags, HasSlug, SoftDeletes;

    protected $fillable = ['title', 'description', 'author', 'status', 'featured'];

    protected $casts = [
        'status' => PostStatus::class,
        'seo_keywords' => 'array',
    ];

    public function groups()
    {
        return $this->belongsToMany(PostGroup::class, 'post_post_group');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', PostStatus::Draft);
    }

    public function scopePublished($query)
    {
        return $query->where('status', PostStatus::Published);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
}
