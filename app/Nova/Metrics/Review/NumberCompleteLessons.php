<?php

namespace App\Nova\Metrics\Review;

use App\Models\ExaminationMockQuiz;
use App\Models\Lesson;
use App\Models\LessonUser;
use App\Models\User;
use App\Nova\Filters\EndTimeFilter;
use App\Nova\Filters\StartTimeFilter;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class NumberCompleteLessons extends Value
{
    use GlobalFilterable;
    public $icon = 'user-group';

    public function name()
    {
        return __('Total number of times to complete the review lesson');
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        // Filter your model with existing filters
        $model = $this->globalFiltered($request, LessonUser::class,[
            StartTimeFilter::class,
            EndTimeFilter::class
        ]);
        // withoutSuffixInflection delete auto add suffix 's'
        return $this->result($model->where('is_complete', true)->count())->suffix(__('times'))->withoutSuffixInflection();
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [];
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }
}
