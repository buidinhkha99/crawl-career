<?php

namespace App\Nova\LMS;

use App\Nova\Resource;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Topic extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Topic>
     */
    public static string $model = \App\Models\Topic::class;

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

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->withCount('lessons')->withCount('questions');
    }

    public static function detailQuery(NovaRequest $request, $query)
    {
        return $query->withCount('lessons')->withCount('questions');
    }

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')
                ->rules(['required', 'max:100'])
                ->creationRules('unique:topics,name')
                ->updateRules('unique:topics,name,{{resourceId}}')
                ->sortable(),
            Number::make(__('Number Of Questions'), 'questions_count')
                ->sortable()
                ->exceptOnForms(),
            MorphMany::make(__('Questions'), 'questions', Question::class),
            Number::make(__('Number Of Lessons'), "lessons_count")->exceptOnForms(),
            MorphMany::make(__('Lesson'), 'lessons', Lesson::class),
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
        return __('Topics');
    }

    public static function softDeletes()
    {
        return false;
    }
}
