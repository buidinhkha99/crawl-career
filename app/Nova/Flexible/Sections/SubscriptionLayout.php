<?php

namespace App\Nova\Flexible\Sections;

use App\Nova\Flexible\Components\Background;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class SubscriptionLayout extends Layout implements Section
{
    use ComponentRender;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'subscription';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Subscription Section';

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

            Text::make(__('Title'), 'title')
                    ->rules(['required']),

            Textarea::make(__('Content'), 'content'),
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
            'type' => 'Subscription',
            'id' => $id,
            'title' => Arr::get($attributes, 'title', ''),
            'content' => Arr::get($attributes, 'content', ''),
            'background' => $this->backgroundRender(
                Arr::get($attributes, 'background_option', 'color'),
                Arr::get($attributes, 'background', '#000000')
            ),
        ];
    }
}
