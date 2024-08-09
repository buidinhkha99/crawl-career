<?php

namespace App\Nova\Filters;

use App\Models\Question;
use App\Models\Topic;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class TopicQuestionNameFilter extends Filter
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
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        if ($value === 'Not in any group') {
            return $query->doesntHave('topics');
        }

        return $query->select('topics.*', 'questions.*')
            ->join('topicables', 'questions.id', '=', 'topicables.topicable_id')
            ->join('topics', 'topics.id', '=', 'topicables.topic_id')
            ->where('topicables.topicable_type', Question::class)
            ->where('topics.name','=', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function options(NovaRequest $request)
    {
        return [
            ...Topic::select('name')->get()->pluck('name', 'name'),
            __('Not in any group') => 'Not in any group',
        ];
    }
}
