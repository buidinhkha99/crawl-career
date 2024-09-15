<?php

namespace App\Nova\Filters;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class DepartmentCertificateFilter extends MultiselectFilter
{
    public function name()
    {
        return __('Department');
    }

    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->whereJsonContains('card_info->department', $value[0] ?? null);
    }

    public function options(Request $request)
    {
        return [
            ...User::select('department')->distinct()->get()?->pluck('department', 'department'),
        ];
    }
}
