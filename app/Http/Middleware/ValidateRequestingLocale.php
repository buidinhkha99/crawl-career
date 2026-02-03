<?php

namespace App\Http\Middleware;

use Adrianorosa\GeoLocation\GeoLocation;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ValidateRequestingLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Str::startsWith("/{$request->path()}", config('nova.path'))) {
            return $next($request);
        }

        $request->session()->put('lang', $this->requestingLanguage($request));

        return $next($request);
    }

    protected function requestingLanguage(Request $request)
    {
        $default_lang = Setting::get('default_language');
        $languages = Setting::get('languages')->pluck('key');

        if (! $languages->contains($request->cookie('lang'))) {
            $request->cookies->remove('lang');
        }

        if (! $languages->contains($request->query('lang'))) {
            $request->query->remove('lang');
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
