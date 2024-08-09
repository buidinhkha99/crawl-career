<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Outl1ne\NovaMediaHub\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasFactory, HasSlug;

    protected $casts = [
        'media' => 'array',
    ];

    public function getThumbnailAttribute()
    {
        if (! $this->getAttribute('media')) {
            return null;
        }

        return Media::where('id', $this->getAttribute('media'))->first()?->url;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
