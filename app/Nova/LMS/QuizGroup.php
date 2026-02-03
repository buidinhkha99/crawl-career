<?php

namespace App\Nova\LMS;

use App\Nova\Resource;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class QuizGroup extends Resource
{
    use HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\MockQuiz>
     */
    public static string $model = \App\Models\QuizGroup::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return $this->name;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name'
    ];

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')
                ->rules(['required', 'string', 'max:30', function($attribute, $value, $fail) {
                    if (!preg_match("/^[^,]+$/u", $value )) {
                        return $fail(__("Name without comma"));
                    }
                }])
                ->creationRules('unique:quiz_groups,name')
                ->updateRules('unique:quiz_groups,name,{{resourceId}}')
                ->sortable(),
            HasMany::make(__('Quizzes'), 'quizzes', MockQuiz::class),
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
        return __('Quiz Group');
    }
}
