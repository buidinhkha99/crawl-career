<?php

namespace App\Nova\Flexible\Components;

use Alexwenzel\DependencyContainer\DependencyContainer;
use Exception;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\URL;
use Outl1ne\NovaMediaHub\Models\Media;
use Outl1ne\NovaMediaHub\Nova\Fields\MediaHubField;

class Image
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
            Select::make($name_prefix.' '.__('Image Options'), $attribute_prefix.'image_option')->options([
                'none' => 'None',
                'upload' => __('Image Upload'),
                'url' => __('Image URL'),
            ])->default(fn () => 'none')->rules('required'),

            DependencyContainer::make([
                Hidden::make($name_prefix.' '.__('Image'), $attribute_prefix.'image')->default(null),
            ])->dependsOn($attribute_prefix.'image_option', 'none'),

            DependencyContainer::make([
                MediaHubField::make($name_prefix.' '.__('Image Upload'), $attribute_prefix.'image'),
            ])->dependsOn($attribute_prefix.'image_option', 'upload'),

            DependencyContainer::make([
                URL::make($name_prefix.' '.__('Image URL'), $attribute_prefix.'image_url')
                    ->fillUsing(function ($request, $model, $attribute, $requestAttribute) use ($attribute_prefix) {
                        if ($request->input($attribute_prefix.'image_option') === 'upload') {
                            $model->{$attribute} = Media::where('id', $request->input($attribute_prefix.'image'))->first()?->url;

                            return;
                        }
                    }),
            ])->dependsOn($attribute_prefix.'image_option', 'url'),
        ];
    }
}
