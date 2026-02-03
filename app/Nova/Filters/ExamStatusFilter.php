<?php

namespace App\Nova\Filters;

use App\Enums\ExamStatus;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class ExamStatusFilter extends Filter
{
    public function name(): array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        return __('Status');
    }

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     */
    public function apply(NovaRequest $request, $query, $value): \Illuminate\Database\Eloquent\Builder
    {
        if ($value === ExamStatus::Finished()->key) {
            return $query->where('end_at', '<', now());
        }

        if ($value === ExamStatus::Upcoming()->key) {
            return $query->where('start_at', '>', now());
        }

        if ($value === ExamStatus::Happening()->key) {
            return $query->where('start_at', '<=', now())->where('end_at', '>=', now());
        }

        return $query;
    }

    /**
     * Get the filter's available options.
     */
    public function options(NovaRequest $request): array
    {
        return ExamStatus::asSelectArray();
    }
}
