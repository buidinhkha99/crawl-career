<?php

namespace App\Nova\Filters;

use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class EndTimeFilter extends DateFilter
{
    public function name()
    {
        return __('End Time');
    }
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
        $value = Carbon::parse($value . ' 23:59:59');

        return $query->where('created_at', '<=', $value);
    }

    public function default()
    {
        return now()->format('Y-m-d');
    }
}
