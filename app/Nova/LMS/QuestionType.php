<?php

namespace App\Nova\LMS;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class QuestionType extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\QuestionType>
     */
    public static string $model = \App\Models\QuestionType::class;

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
     * Indicates if the resource should be globally searchable.
     *
     * @var bool
     */
    public static $globallySearchable = false;

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make(),
            Text::make(__('Name'), 'name')
                ->rules('required')
                ->creationRules('unique:question_types,name')
                ->updateRules('unique:question_types,name,{{resourceId}}'),
            Select::make(__('Type'), 'type')
                ->options(\App\Enums\QuestionType::asSelectArray())
                ->rules('required')
                ->searchable(),
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
        return __('Question Types');
    }
}
