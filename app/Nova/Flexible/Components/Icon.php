<?php

namespace App\Nova\Flexible\Components;

use AlexAzartsev\Heroicon\Heroicon;
use App\Enums\ButtonType;
use Exception;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Text;

class Icon
{
    /**
     * Execute the preset configuration
     *
     * @return void
     *
     * @throws Exception
     */
    public static function fields($name_prefix = '', $attribute_prefix = ''): array
    {
        return [
            Heroicon::make($name_prefix.' Button Icon', $attribute_prefix.'button_icon'),
            Hidden::make($name_prefix.' Button Type', $attribute_prefix.'button_type')->default(ButtonType::Button),
            Text::make($name_prefix.' Button Link', $attribute_prefix.'button_link')->default('#'),
        ];
    }
}
