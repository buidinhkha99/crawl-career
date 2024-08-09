<?php

namespace App\Nova\Filters;

use App\Models\Topic;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class GroupNameFilter extends MultiselectFilter
{
    public function name()
    {
        return __('Group User');
    }

    public function apply(NovaRequest $request, $query, $value)
    {
        if (Arr::get($value, 0) === 'Not in any group') {
            return $query->doesntHave('groups');
        }

        return $query->whereHas('groups', fn ($q) => $q->whereIn('name', $value));
    }

    public function options(Request $request)
    {
        return [
            ...UserGroup::select('name')->distinct()->get()?->pluck('name', 'name'),
            'Not in any group' => __('Not in any group'),
        ];
    }
}
