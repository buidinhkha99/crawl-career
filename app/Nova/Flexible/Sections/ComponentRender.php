<?php

namespace App\Nova\Flexible\Sections;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Outl1ne\NovaMediaHub\Models\Media;

trait ComponentRender
{
    protected $locale = null;

    protected $language = null;

    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    public function setLocale($locale = null)
    {
        $this->locale = $locale;

        return $this;
    }

    // @TODO layout
    protected function logoRender($attributes): array
    {
        return [
            'img' => Arr::get($attributes, 'logo_image_url', ''),
            'text' => Arr::get($attributes, 'logo_text', null),
            'color' => Arr::get($attributes, 'logo_text_color', null),
            'font' => Arr::get($attributes, 'logo_text_font', null),
            'url' => Arr::get($attributes, 'logo_url', '/'),
            'layout' => 'logo-image-text-horizontal',
        ];
    }

    protected function backgroundRender($option, $value): ?array
    {
        switch ($option) {
            case 'image_upload':
                return [
                    'type' => 'url',
                    'data' => Media::where('id', $value)->first()?->url,
                ];
            case 'image_url':
                return [
                    'type' => 'url',
                    'data' => $value,
                ];
            default:
                return [
                    'type' => 'color',
                    'data' => $value,
                ];
        }
    }

    protected function translate($text)
    {
        if ($this->language == $this->locale || $this->locale === null || empty($text)) {
            return $text;
        }

        // try-catch in case api translation failed => we fall back to original text
        try {
            return Str::apiTranslate($text, $this->locale);
        } catch (\Exception $e) {
            return $text;
        }
    }
}
