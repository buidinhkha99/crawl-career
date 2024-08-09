<?php

namespace App\Traits;

use Adrianorosa\GeoLocation\GeoLocation;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

trait RenderLanguageTrait
{
    public function renderLanguage(Request $request, string $default_lang = null)
    {
        if ($default_lang === null) {
            $default_lang = Setting::get('default_language');
        }

        try {
            // GeoLocation may throw exception when API limit reached
            $geo = GeoLocation::lookup($request->ip());

            $requested_country = $geo->getCountryCode() ?? '';
            $country_lang = Setting::get('country_language')->mapWithKeys(fn ($item) => [$item['country'] => $item['language']]);

            $lang = Arr::get($country_lang, $requested_country, $default_lang);

            return $request->cookie('lang') ?? $request->query('lang') ?? $request->route()->getPrefix() ?? $lang;
        } catch (\Exception $e) {
            return $request->cookie('lang') ?? $request->query('lang') ?? $request->route()->getPrefix() ?? $default_lang;
        }
    }
}
