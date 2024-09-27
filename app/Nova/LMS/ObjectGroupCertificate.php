<?php

namespace App\Nova\LMS;

use App\Nova\Resource;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class ObjectGroupCertificate extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Topic>
     */
    public static string $model = \App\Models\ObjectGroup::class;

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
        'description',
    ];

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')
                ->rules(['required'])
                ->creationRules('unique:object_groups,name')
                ->updateRules('unique:object_groups,name,{{resourceId}}')
                ->sortable(),
            Textarea::make(__('Description'), 'description')->required()->sortable()
        ];
    }

    /**
     * Get the cards available for the request.
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }

    public static function label(): string
    {
        return __('Object Group Certificate');
    }

    public static function softDeletes()
    {
        return false;
    }
}
