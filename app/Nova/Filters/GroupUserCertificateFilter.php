<?php

namespace App\Nova\Filters;

use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class GroupUserCertificateFilter extends MultiselectFilter
{
    public function name()
    {
        return __('Group User');
    }

    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->whereHas('group', fn ($q) => $q->whereIn('name', $value));
    }

    public function options(Request $request)
    {
        return [
            ...UserGroup::select('name')->distinct()->get()?->pluck('name', 'name'),
        ];
    }
}
