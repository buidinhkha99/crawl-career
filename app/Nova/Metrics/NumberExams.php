<?php

namespace App\Nova\Metrics;

use App\Models\Exam;
use App\Models\User;
use App\Nova\Filters\EndTimeFilter;
use App\Nova\Filters\StartTimeFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class NumberExams extends Value
{
    public $icon = 'document-text';
    public function name()
    {
        return __('Total Number Exams');
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
        return $this->result(Exam::select('id')->count())->suffix(__('exams'))->withoutSuffixInflection();
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
