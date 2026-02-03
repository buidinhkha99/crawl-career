<?php

namespace App\Nova\Filters;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class PositionCertificateFilter extends MultiselectFilter
{
    public function name()
    {
        return __('Position');
    }

    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->whereHas('user', function ($query) use ($value) {
            $query->where('position', $value[0] ?? null);
        });
    }

    public function options(Request $request)
    {
        return User::select('position')->whereNotNull('position')->distinct()->get()?->pluck('position', 'position');
    }
}
