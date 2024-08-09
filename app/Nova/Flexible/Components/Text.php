<?php

namespace App\Nova\Flexible\Components;

use App\Enums\Font;
use Exception;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text as NovaText;
use Outl1ne\NovaColorField\Color;

class Text
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
            NovaText::make($name_prefix.' '.__('Text'), $attribute_prefix.'text'),
            Color::make($name_prefix.' '.__('Text Color'), $attribute_prefix.'text_color')
                ->swatches()
                ->default('#FFFFFF'),
            Select::make($name_prefix.' '.__('Text Font'), $attribute_prefix.'text_font')
                ->options(Font::asSelectArray())
                ->default(Font::Poppins)->rules(['required']),
        ];
    }
}
