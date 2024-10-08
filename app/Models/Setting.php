<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Outl1ne\NovaSettings\Models\Settings;
use Outl1ne\NovaSettings\NovaSettings;

class Setting extends Settings
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'country_language' => 'collection',
        'languages' => 'collection',
        'tables_search' => 'collection',
        'error_pages' => 'collection',
        'kit' => 'collection',
        'complete_from' => 'date',
        'complete_to' => 'date',
        'effective_to' => 'date',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        NovaSettings::addCasts($this->casts);
    }

    public static function set($key, $value = null)
    {
        nova_set_setting_value($key, $value);
    }

    public static function all($keys = null, $defaults = [])
    {
        $result = collect([]);
        foreach ($keys as $key) {
            $result = $result->put($key, self::get($key, $defaults[$key] ?? null));
        }

        return $result;
    }

    public static function get($key, $default = null)
    {
        switch ($key) {
            case 'default_language':
                return self::get_default_language();
            default:
                return Cache::tags(['settings'])->rememberForever($key, fn () => nova_get_setting($key, $default) ?? '');
        }
    }

    private static function get_default_language()
    {
        $setting = self::get('languages');

        return $setting->firstWhere('default', true)['key'];
    }
}
