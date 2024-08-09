<?php

namespace App\Nova\Flexible\Components;

use Alexwenzel\DependencyContainer\DependencyContainer;
use Exception;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\URL;
use Outl1ne\NovaColorField\Color;
use Outl1ne\NovaMediaHub\Nova\Fields\MediaHubField;

class Background
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
            Select::make($name_prefix.__('Background Options'), $attribute_prefix.'background_option')->options([
                'color' => __('Color Picker'),
                'image_upload' => __('Image Upload'),
                'image_url' => __('Image URL'),
            ])->default('color')->rules('required'),

            DependencyContainer::make([
                Color::make($name_prefix.__('Background Color'), $attribute_prefix.'background')
                    ->swatches(),
            ])->dependsOn($attribute_prefix.'background_option', 'color'),

            DependencyContainer::make([
                MediaHubField::make($name_prefix.__('Background Image'), $attribute_prefix.'background'),
            ])->dependsOn($attribute_prefix.'background_option', 'image_upload'),

            DependencyContainer::make([
                URL::make($name_prefix.__('Background Image URL'), $attribute_prefix.'background'),
            ])->dependsOn($attribute_prefix.'background_option', 'image_url'),
        ];
    }
}
