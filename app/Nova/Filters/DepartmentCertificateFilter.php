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
        return $query->whereHas('user', function ($query) use ($value) {
            $query->where('department', $value[0] ?? null);
        });
    }

    public function options(Request $request)
    {
        return User::select('department')->whereNotNull('department')->distinct()->get()?->pluck('department', 'department');
    }
}
