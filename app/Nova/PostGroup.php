<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Trin4ik\NovaSwitcher\NovaSwitcher;

class PostGroup extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\PostGroup>
     */
    public static $model = \App\Models\PostGroup::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')->required()->rules('required'),
            BelongsTo::make(__('Parent'), 'parent', self::class)->nullable(),
            HasMany::make(__('Children'), 'children', self::class)->nullable(),
            BelongsToMany::make(__('Posts'), 'posts', Post::class),
            NovaSwitcher::make(__('Status'), 'enabled')->withLabels(true: 'Enabled', false: 'Disabled')->default(true),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    public static function label(): string
    {
        return __('Post Group');
    }
}
