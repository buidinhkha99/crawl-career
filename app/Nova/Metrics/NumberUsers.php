<?php

namespace App\Nova\Metrics;

use App\Models\Exam;
use App\Models\User;
use App\Nova\Filters\EndTimeFilter;
use App\Nova\Filters\StartTimeFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class NumberUsers extends Value
{
    public $icon = 'user-group';

    public function name()
    {
        return __('Total Number Users');
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {

        // withoutSuffixInflection delete auto add suffix 's'
        return $this->result(User::select('id')->manageRoles()->count())->suffix(__('user'))->withoutSuffixInflection();
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

    // remote global filter
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'icon' => $this->icon,
            'filterable' => false,
        ]);
    }
}
