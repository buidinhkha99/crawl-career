<?php

namespace App\Nova\LMS\AcademicReport;

use App\Enums\ExaminationStatus;
use App\Nova\Filters\ExamNameFilter;
use App\Nova\Filters\QuizNameFilter;
use App\Nova\Resource;
use App\Nova\User;
use Dnwjn\NovaButton\Button;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Murdercode\TinymceEditor\TinymceEditor;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;

class ExaminationInMockQuiz extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var
     */
    public static string $model = \App\Models\ExaminationMockQuiz::class;

    public function title(): string
    {
        return "$this->employee_code - $this->name - $this->quiz_name - ".$this->start_time?->format('d/m/Y');
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'employee_code', 'username',
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
            ID::make()->sortable(),
            Text::make(__('Employee Code'), 'employee_code')
                ->sortable(),
            Text::make(__('User'), 'name', function () {
                if (empty($this->user)) {
                    return "<p>$this->name</p>";
                }

                return '<a class="link-default" href="'.sprintf(
                        '%s/resources/%s/%d', config('nova.path'),
                        User::uriKey(), $this->user_id).'">'.$this->name.'</a>';
            })->asHtml(),
            Text::make(__('Group User'), 'group')->onlyOnDetail(),
            Text::make(__('Duration'), 'duration')->displayUsing(fn ($value) => $value ? gmdate('H:i:s', $value) : null)->sortable(),
            DateTime::make(__('Start Time'), 'start_time')->displayUsing(fn ($value) => $value ? $value->format('d/m/Y H:i:s') : null),
            DateTime::make(__('End Time'), 'end_time')->displayUsing(fn ($value) => $value ? $value->format('d/m/Y H:i:s') : null),
            Text::make(__('Score'), 'score'),
            Badge::make(__('Result'), 'state')->map([
                ExaminationStatus::Fail => 'danger',
                ExaminationStatus::Pass => 'success',
                ExaminationStatus::NoExam => 'warning',
            ])->labels([
                ExaminationStatus::Fail => __('Fail'),
                ExaminationStatus::Pass => __('Pass'),
                ExaminationStatus::NoExam => __('Not Exam'),
            ]),
//            Button::make(__('Print the result'))
//                ->style($this->state === ExaminationStatus::NoExam ? 'gray' : 'primary')
//                ->link('/media/examination/'.$this?->uuid)
//                ->disabled($this->state === ExaminationStatus::NoExam)
//                ->hideFromDetail($this->state === ExaminationStatus::NoExam),

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
            ]),
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
        return __('Mock Tests');
    }

    public static function softDeletes(): bool
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    public function authorizedToAttachAny(NovaRequest $request, $model)
    {
        return false;
    }

    /**
     * Determine if the user can attach models of the given type to the resource.
     *
     * @param NovaRequest $request
     * @param Model|string $model
     * @return bool
     */
    public function authorizedToAttach(NovaRequest $request, $model)
    {
        return false;
    }

    public function authorizedToDetach(NovaRequest $request, $model, $relationship)
    {
        return false;
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToUpdate(Request $request)
    {
        return false;
    }

    public function authorizedToReplicate(Request $request)
    {
        return false;
    }
}
