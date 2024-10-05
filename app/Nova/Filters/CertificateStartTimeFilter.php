<?php

namespace App\Nova\Filters;

use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class CertificateStartTimeFilter extends DateFilter
{
    public function name()
    {
        return __('Training start date');
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
        $value = Carbon::parse($value)->format('Y-m-d');

        return $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(card_info, '$.complete_from')) = ?", [$value]);
    }
}
