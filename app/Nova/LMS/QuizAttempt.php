<?php

namespace App\Nova\LMS;

use App\Enums\ExaminationStatus;
use App\Enums\QuizType;
use App\Nova\Resource;
use App\Nova\Traits\HasCallbacks;
use App\Nova\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Murdercode\TinymceEditor\TinymceEditor;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class QuizAttempt extends Resource
{
    use HasCallbacks, HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\MockQuiz>
     */
    public static string $model = \App\Models\QuizAttempt::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        if (\request()->get('search')) {
            return 'ID: ' . $this->quiz->id . '-' . $this->quiz->name;
        }

        return $this->quiz->name ?? $this->quiz->id;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make(),
            Text::make(__('Quiz'), 'quiz->id', function () {
                return '<a class="link-default" href="'.sprintf(
                        '%s/resources/%s/%d', config('nova.path'),
                        Quiz::uriKey(), $this->quiz->id) . '">' . ($this->quiz->name ?? $this->quiz->id) . '</a>';
            })->asHtml(),
            Number::make(__('Duration'), 'quiz->duration'),
            Number::make(__('Question Amount'), 'quiz->question_amount_quiz'),
            Number::make(__('Score Pass'), 'quiz->score_pass_quiz'),
            Badge::make(__('Result'), 'state')->map([
                false => 'danger',
                true => 'success',
            ])->labels([
                false => __('Fail'),
                true => __('Pass'),
            ])->filterable(),
            SimpleRepeatable::make(__('Examination'), 'examination', [
                Text::make(__('Order Question'), 'order')->displayUsing(fn ($value) => $value ? __('Order Question')." $value" : ''),
                TinymceEditor::make(__('Question'), 'question_content'),
                Boolean::make(__('Right answer'), 'is_correct'),
                SimpleRepeatable::make(__('Answer sheet'), 'answers', [
                    TinymceEditor::make(__('AnswerQ'), 'data'),
                    Boolean::make(__('Correct Answer'), 'is_correct'),
                    Badge::make(__('Answered'), 'is_choose')->map([
                        0 => 'warning',
                        1 => 'info',
                        null => 'warning',
                    ])->icons([
                        'info' => 'check-circle',
                        'warning' => '',
                    ])->types([
                        'warning' => 'colors-primary-50',
                        'info' => 'colors-primary-50',
                    ])->label(function ($value) {
                        return null;
                    }),
                ]),
            ])->onlyOnDetail(),
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
        return __('Mock Quiz Done');
    }
}
