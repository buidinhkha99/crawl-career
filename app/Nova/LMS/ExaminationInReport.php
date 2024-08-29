<?php

namespace App\Nova\LMS;

use App\Enums\ExaminationStatus;
use App\Models\Examination;
use App\Nova\Actions\DownloadExcel;
use App\Nova\Actions\DownloadPDFReport;
use App\Nova\Filters\ExamNameFilter;
use App\Nova\Filters\QuizNameFilter;
use App\Nova\Filters\ReportDurationExamFilter;
use App\Nova\Filters\ReportScoreFilter;
use App\Nova\Filters\UserGroupNameFilter;
use App\Nova\Resource;
use App\Nova\User;
use Dnwjn\NovaButton\Button;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Murdercode\TinymceEditor\TinymceEditor;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;

class ExaminationInReport extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Examination>
     */
    public static string $model = \App\Models\Examination::class;

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

    public static $perPageViaRelationship = 50;

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->with('exam')->with('quiz')->with('user');
    }

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('User'), 'name', function () {
                if (empty($this->user)) {
                    return "<p>$this->name</p>";
                }

                return '<a class="link-default" href="'.sprintf(
                        '%s/resources/%s/%d', config('nova.path'),
                        User::uriKey(), $this->user_id).'">'.$this->name.'</a>';
            })->asHtml(),

            Text::make(__('Employee Code'), 'employee_code')
                ->sortable(),
            Text::make(__('CCCD/CMND'), 'username'),
            Text::make(__('Group User'), 'group')->onlyOnDetail(),

            Text::make(__('Quiz'), 'quiz_name', function () {
                if (empty($this->quiz)) {
                    return "<p>$this->quiz_name</p>";
                }

                return '<a class="link-default" href="'.sprintf(
                    '%s/resources/%s/%d', config('nova.path'),
                    Quiz::uriKey(), $this->quiz_id).'">'.$this->quiz_name.'</a>';
            })->asHtml(),

            Text::make(__('Exam'), 'exam_name', function () {
                if (empty($this->exam)) {
                    return "<p>$this->exam_name</p>";
                }

                return '<a class="link-default" href="'.sprintf(
                    '%s/resources/%s/%d', config('nova.path'),
                    Exam::uriKey(), $this->exam_id).'">'.$this->exam_name.'</a>';
            })->asHtml(),

            DateTime::make(__('Exam date'), 'start_time')->displayUsing(fn ($value) => $value ? $value->format('d/m/Y') : '')->filterable()->sortable(),
            Text::make(__('Score'), 'score')->sortable(),
            Badge::make(__('Result'), 'state')->map([
                ExaminationStatus::Fail => 'danger',
                ExaminationStatus::Pass => 'success',
                ExaminationStatus::NoExam => 'warning',
            ])->labels([
                ExaminationStatus::Fail => __('Fail'),
                ExaminationStatus::Pass => __('Pass'),
                ExaminationStatus::NoExam => __('Not Exam'),
            ])->filterable(),
            Text::make(__('Duration'), 'duration')->displayUsing(fn ($value) => $value ? gmdate('H:i:s', $value) : null)->sortable(),
            Button::make(__('Print the result'))
                ->style($this->state === ExaminationStatus::NoExam ? 'gray' : 'primary')
                ->link('/media/examination/'.$this?->uuid)
                ->disabled($this->state === ExaminationStatus::NoExam)
                ->hideFromDetail($this->state === ExaminationStatus::NoExam),
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
        return [
            (new ExamNameFilter())->singleSelect(),
            (new QuizNameFilter())->singleSelect(),
            (new UserGroupNameFilter())->singleSelect(),
            (new ReportScoreFilter())->singleSelect(),
            (new ReportDurationExamFilter())->singleSelect(),
        ];
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
        return [
            (new DownloadExcel)
                ->canRun(fn () => $request->user()->can('viewAny', Examination::class))
                ->confirmButtonText(__('Download'))
                ->cancelButtonText(__('Cancel'))
                ->confirmText(__('Select the information field to export the file'))
                ->onlyOnIndex(),
            (new DownloadPDFReport)
                ->canRun(fn () => $request->user()->can('viewAny', Examination::class))
                ->confirmButtonText(__('Download'))
                ->cancelButtonText(__('Cancel'))
                ->confirmText(__('Select the information field to export the file'))
                ->onlyOnIndex(),
        ];
    }

    public static function label(): string
    {
        return __('Report');
    }
}
