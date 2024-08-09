<?php

namespace App\Nova\Filters;

use App\Models\Examination;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class QuizNameFilter extends MultiselectFilter
{
    public function name()
    {
        return __('Quiz');
    }

    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->whereIn('quiz_name', $value);
    }

    public function options(Request $request)
    {
        return Examination::select('quiz_name')->distinct()->get()?->pluck('quiz_name', 'quiz_name');
    }
}
