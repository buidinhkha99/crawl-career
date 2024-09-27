<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Outl1ne\NovaMediaHub\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ObjectGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];
}
