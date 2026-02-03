<?php

namespace App\Nova\Filters;

use App\Enums\ExamStatus;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class QuizStatusFilter extends Filter
{
    public function name()
    {
        return __('Status');
    }
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->whereHas('exam', function (Builder $query) use ($value){

            if ($value == 'Upcoming') {
                return $query->where('start_at', '>', now());
            }

            if ($value == 'Finished') {
                return $query->where('end_at', '<', now());
            }

            return $query->where('start_at', '<', now())->where('end_at', '>', now());
        });
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function options(NovaRequest $request)
    {
        return ExamStatus::asSelectArray();
    }
}
