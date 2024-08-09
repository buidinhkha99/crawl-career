<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Customization extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'content'];

    public static function get($key, $default = '')
    {
        return Cache::tags(['customizations'])
            ->rememberForever($key, fn () => self::where('type', $key)->first()?->getAttribute('content') ?? $default);
    }
}
