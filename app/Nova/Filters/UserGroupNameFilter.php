<?php

namespace App\Nova\Filters;

use App\Models\Examination;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class UserGroupNameFilter extends MultiselectFilter
{
    public function name()
    {
        return __('Group User');
    }

    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->where('group', ($value[0] ?? null))
                     ->orWhere('group', 'LIKE',($value[0] ?? null) . ',' . '%')
                     ->orWhere('group', 'LIKE', '%'. ', ' . ($value[0] ?? null) . ',' . '%')
                     ->orWhere('group', 'LIKE', '%'. ', ' . ($value[0] ?? null));
    }

    public function options(Request $request)
    {
        return UserGroup::select('name')->get()?->pluck('name', 'name');
    }
}
