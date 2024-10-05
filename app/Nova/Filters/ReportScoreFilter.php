<?php

namespace App\Nova\Filters;

use App\Models\UserGroup;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class ReportScoreFilter extends MultiselectFilter
{
    public function name()
    {
        return __('Score examination');
    }

    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->whereNotNull('score')->orderBy('score', $value[0] ?? 'asc');
    }

    public function options(Request $request)
    {
        return [
            'asc' => __('Low to high'),
            'desc' => __('High to low'),
        ];
    }
}
