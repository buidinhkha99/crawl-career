<?php

namespace App\Traits;

use App\Models\Setting;
use Outl1ne\NovaMediaHub\Models\Media;

trait RenderSettingGlobalTrait
{
    public function getSettingGlobal()
    {
        return collect(Setting::all([
            'background_option', 'background',
            'font_color', 'font_name', 'font_url',
            'favicon',
            'button_color_background', 'button_icon_color', 'button_text_color',
            'color_text_title_form', 'background_input_form', 'color_border_input_form', 'color_placeholder_input_form',
        ], []))->map(fn ($value, $key) => $key === 'background' ? $this->backgroundRender(Setting::get('background_option'), $value) : $value);
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
}
