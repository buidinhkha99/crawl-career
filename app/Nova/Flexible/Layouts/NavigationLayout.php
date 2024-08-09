<?php

namespace App\Nova\Flexible\Layouts;

use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class NavigationLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'navigations';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Navigations';

    /**
     * The maximum amount of this layout type that can be added
     */
    protected $limit = 8;

    /**
     * Get the fields displayed by the layout.
     *
     * @throws \Exception
     */
    public function fields(): array
    {
        return [
            Text::make(__('Title'), 'title')->rules(['required']),
            Text::make('URL', 'url')->rules(['required']),
        ];
    }

    public function collapsedPreviewAttribute()
    {
        return 'title';
    }
}
