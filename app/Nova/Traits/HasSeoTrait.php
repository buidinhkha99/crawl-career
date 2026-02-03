<?php

namespace App\Nova\Traits;

use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Outl1ne\MultiselectField\Multiselect;
use Outl1ne\NovaMediaHub\Models\Media;
use Outl1ne\NovaMediaHub\Nova\Fields\MediaHubField;

trait HasSeoTrait
{
    public function seoFields()
    {
        return [
            Text::make(__('Title'), 'seo_title')
                ->hideFromIndex()
                ->help('Your title should be between 30-60 characters, with a maximum of 90'),

            Textarea::make(__('Description'), 'seo_description')
                ->hideFromIndex()
                ->help('The length of your description should be 149 characters'),

            Multiselect::make(__('Keywords'), 'seo_keywords')
                ->hideFromIndex()
                ->taggable(),

            MediaHubField::make(__('Open Graph Image'), 'seo_og_image')
                ->hideFromIndex()
                ->help('The recommended image ratio for an og:image is 1.91:1. The optimal size would be 1200 x 630. Image size should be (<8mb)'),

            Hidden::make(__('Open Graph Image URL'), 'seo_og_image_url')->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                $model->{$attribute} = Media::find($request->input('seo_og_image'))?->url;
            }),
        ];
    }
}
