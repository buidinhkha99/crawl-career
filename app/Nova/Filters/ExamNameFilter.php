<?php

namespace App\Nova\Filters;

use App\Models\Examination;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class ExamNameFilter extends MultiselectFilter
{
    public function name()
    {
        return __('Exam');
    }

    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->whereIn('exam_name', $value);
    }

    public function options(Request $request)
    {
        return Examination::select(['exam_name', 'created_at'])->latest()->distinct()->get()?->pluck('exam_name', 'exam_name');
    }
}
