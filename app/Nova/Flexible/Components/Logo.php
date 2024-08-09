<?php

namespace App\Nova\Flexible\Components;

use Alexwenzel\DependencyContainer\DependencyContainer;
use Exception;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text as NovaText;

class Logo
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
        $name_prefix = $name_prefix.' Logo';
        $attribute_prefix = $attribute_prefix.'logo_';

        return [
            ...Image::fields($name_prefix, $attribute_prefix),

            NovaText::make($name_prefix.' URL', $attribute_prefix.'url')->default('/'),

            Boolean::make($name_prefix.' '.__('Text'), $attribute_prefix.'text_option')->default(false),

            DependencyContainer::make(
                Text::fields($name_prefix, $attribute_prefix)
            )->dependsOn($attribute_prefix.'text_option', true),
        ];
    }
}
