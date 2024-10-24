<?php

namespace App\Nova\Filters;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class UserFilter extends MultiselectFilter
{
    public function name()
    {
        return __('User');
    }

    /**
     * Apply the filter to the given query.
     *
     * @param NovaRequest $request
     * @param Builder $query
     * @param mixed $value
     * @return Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->where('user_id', '=', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function options(NovaRequest $request)
    {
        return User::select(['id', 'name', 'employee_code'])->whereHas('roles', function ($query) {
            $query->where('name', '!=', Role::SUPER_ADMIN);
        })->orWhereDoesntHave('roles')->get()->mapWithKeys(function ($user) {
            return [$user->id => $user->employee_code . ' - ' . $user->name];
        })->toArray();
    }
}
