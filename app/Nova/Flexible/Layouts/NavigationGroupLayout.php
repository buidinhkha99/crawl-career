<?php

namespace App\Nova\Flexible\Layouts;

use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class NavigationGroupLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'navigation-group';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Navigation Group';

    /**
     * Get the fields displayed by the layout.
     *
     * @throws \Exception
     */
    public function fields(): array
    {
        return [
            Text::make(__('Title'), 'title'),
            Text::make('Url', 'url'),
            Flexible::make(__('Items'), 'items')
                ->fullWidth()
                ->addLayout(NavigationLayout::class)
                ->button(__('Add Navigation')),
        ];
    }

    public function collapsedPreviewAttribute()
    {
        return 'title';
    }
}
