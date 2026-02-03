<?php

namespace App\Nova\Filters;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class TopicQuestionDoneFilter extends MultiselectFilter
{
    public function name()
    {
        return __('Topic');
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
     * @param NovaRequest $request
     * @param Builder $query
     * @param mixed $value
     * @return Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->where('topic_id', '=', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function options(NovaRequest $request)
    {
        return Topic::select('id', 'name')->get()->pluck('name', 'id')->toArray();
    }
}
