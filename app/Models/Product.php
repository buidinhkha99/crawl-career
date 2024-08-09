<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Outl1ne\NovaMediaHub\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory, HasSlug;

    protected $casts = [
        'media' => 'array',
    ];

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function getThumbnailAttribute()
    {
        if (! $this->getAttribute('media') || count($this->getAttribute('media')) <= 0) {
            return null;
        }

        return Media::whereIn('id', $this->getAttribute('media'))->where('mime_type', 'LIKE', '%image/%')->first()?->url;
    }

    public function getMediasAttribute()
    {
        if (! $this->getAttribute('media') || count($this->getAttribute('media')) <= 0) {
            return null;
        }

        return Media::whereIn('id', $this->getAttribute('media'))->get()?->pluck('url');
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
