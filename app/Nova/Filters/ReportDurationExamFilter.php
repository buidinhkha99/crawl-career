<?php

namespace App\Nova\Filters;

use App\Models\UserGroup;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class ReportDurationExamFilter extends MultiselectFilter
{
    public function name()
    {
        return __('Duration examination');
    }

    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->whereNotNull('duration')->orderBy('duration', $value[0] ?? 'asc');
    }

    public function options(Request $request)
    {
        return [
            'asc' => __('Fast to slow'),
            'desc' => __('Slow to fast'),
        ];
    }
}
