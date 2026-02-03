<?php

namespace App\Nova\Flexible\Components;

use AlexAzartsev\Heroicon\Heroicon;
use Alexwenzel\DependencyContainer\DependencyContainer;
use App\Enums\ButtonType;
use Exception;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class Button
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
            Select::make($name_prefix.' Button Type', $attribute_prefix.'button_type')->options([
                'button' => 'Button',
                'icon' => __('Icon'),
            ])->default('button'),

            DependencyContainer::make([
                Boolean::make($name_prefix.' Button Icon?', $attribute_prefix.'button_icon_option')
                    ->trueValue(true)
                    ->falseValue(false),

                DependencyContainer::make([
                    Heroicon::make($name_prefix.' Button Icon', $attribute_prefix.'button_icon'),
                ])->dependsOn($attribute_prefix.'button_icon_option', true),

                Text::make($name_prefix.' Button Text', $attribute_prefix.'button_text'),
                Hidden::make($name_prefix.' Button Type', $attribute_prefix.'button_type')->default(ButtonType::Button),
                Text::make($name_prefix.' Button Link', $attribute_prefix.'button_link')->default('#'),
            ])->dependsOn($attribute_prefix.'button_type', 'button'),

            DependencyContainer::make(Icon::fields($name_prefix, $attribute_prefix))->dependsOn($attribute_prefix.'button_type', 'icon'),
        ];
    }
}
