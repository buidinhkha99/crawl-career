<?php

namespace App\Nova\Metrics;

use App\Models\Lesson;
use App\Models\MockQuiz;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class NumberMockQuizzes extends Value
{
    public $icon = 'document-text';
    public function name()
    {
        return __('Total Number Mock Quizzes');
    }

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $this->withMeta([
            'filterable' => false,
        ]);
        return $this->result(MockQuiz::select('id')->count())->suffix(__('quizzes'))->withoutSuffixInflection();
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

    // remote global filter
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'icon' => $this->icon,
            'filterable' => false,
        ]);
    }
}
