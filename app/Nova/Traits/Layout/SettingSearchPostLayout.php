<?php

namespace App\Nova\Traits\Layout;

use App\Models\PostGroup;
use Laravel\Nova\Fields\Text;
use Outl1ne\MultiselectField\Multiselect;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class SettingSearchPostLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    // set with name table
    protected $name = 'posts';

    protected $limit = 1;

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Post';

    /**
     * Get the fields displayed by the layout.
     *
     * @throws \Exception
     */
    public function fields(): array
    {
        return [
            Text::make(__('Path Details'), 'path_detail_post')->rules(['required']),
            Multiselect::make(__('Search With Groups'), 'group_post_search')
                ->options(fn () => PostGroup::all()->pluck('name', 'id'))
                ->help('if no group is selected, search all groups'),
        ];
    }
}
