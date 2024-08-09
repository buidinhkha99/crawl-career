<?php

namespace App\Models;

class Tag extends \Spatie\Tags\Tag
{
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }
}
