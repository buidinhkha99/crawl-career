<?php

namespace App\Observers;

use App\Jobs\CacheSectionStructureByLocale;
use App\Models\Section;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SectionObserver
{
    public function saved(Section $section)
    {
        // cache this section in all locales
        foreach (Setting::get('languages')->pluck('key') as $locale) {
            CacheSectionStructureByLocale::dispatch($section, $locale)->onQueue('default');
        }
    }

    public function deleted(Section $section)
    {
        // forget this cached section data in every locale
        // there may be cases this section is not cacheable
        foreach (Setting::get('languages')->pluck('key') as $locale) {
            Cache::forget($section->structure_cache_key($locale));
        }
    }
}
