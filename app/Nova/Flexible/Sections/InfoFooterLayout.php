<?php

namespace App\Nova\Flexible\Sections;

use App\Nova\Flexible\Components\Background;
use App\Nova\Flexible\Components\Image;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class InfoFooterLayout extends Layout implements Section
{
    use ComponentRender;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'info-footer';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'InfoFooter Section';

    /**
     * Get the fields displayed by the layout.
     *
     * @throws Exception
     */
    public function fields(): array
    {
        return [
            Text::make(__('Name'), 'name')
                ->rules(['required'])
                ->sortable(),
            Text::make(__('Key'), 'key')
                ->sortable(),

            ...Background::fields(),

            ...Image::fields(),

            Text::make(__('Title'), 'title')
                    ->rules(['required']),
            Text::make(__('Address'), 'address')
                    ->rules(['required']),
            Text::make(__('Phone'), 'phone')
                    ->rules(['required']),
            Text::make(__('Website'), 'website')
                    ->rules(['required']),
        ];
    }

    public function collapsedPreviewAttribute()
    {
        return 'name';
    }

    public function cacheable(): bool
    {
        return false;
    }

    public function cacheableData(): array
    {
        return [];
    }

    public function render(Request $request, $id = null): mixed
    {
        $attributes = $this->attributes;

        return [
            'type' => 'InfoFooter',
            'id' => $id,
            'background' => $this->backgroundRender(
                Arr::get($attributes, 'background_option', 'color'),
                Arr::get($attributes, 'background', '#000000')
            ),
            'title' => Arr::get($attributes, 'title', ''),
            'address' => Arr::get($attributes, 'address', ''),
            'phone' => Arr::get($attributes, 'phone', ''),
            'website' => Arr::get($attributes, 'website', ''),
            'img' => Arr::get($attributes, 'image_url', ''),
        ];
    }
}
