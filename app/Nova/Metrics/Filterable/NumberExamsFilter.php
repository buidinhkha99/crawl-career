<?php

namespace App\Nova\Metrics\Filterable;

use App\Models\Exam;
use App\Nova\Filters\EndTimeFilter;
use App\Nova\Filters\StartTimeFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class NumberExamsFilter extends Value
{
    use GlobalFilterable;
    public $icon = 'document-text';
    public function name()
    {
        return __('Number Exams');
    }

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        // Filter your model with existing filters
        $model = $this->globalFiltered($request, Exam::class,[
            StartTimeFilter::class,
            EndTimeFilter::class
        ]);
        return $this->result($model->select('id')->count())->suffix(__('exams'))->withoutSuffixInflection();
    }


    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Prepare the metric for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'icon' => $this->icon,
        ]);
    }

    public function globalFiltered($request, $model, $filters = [])
    {

        $model = $model instanceof Builder ? $model : (new $model)->newQuery();
        $start_time = null;
        $end_time = null;
        // manage filters changed on UI
        if ($request->has('filters')) {

            $request->range = optional($request)->range ?? 3600;
            $filter_request = json_decode($request->filters, true);

            // need to apply default filters if they are not changed on UI
            foreach ($filters as $filter) {

                $currentFilter = new $filter;
                if ($filter == StartTimeFilter::class) {
                    $value = Arr::get($filter_request, $filter);
                    if (empty($value)) {
                        if (!empty($currentFilter->default())) {
                            $start_time = $currentFilter->default();
                        }
                        continue;
                    }
                    $start_time = $value;
                }

                if ($filter == EndTimeFilter::class) {
                    $value = Arr::get($filter_request, $filter);
                    if (empty($value)) {
                        if (!empty($currentFilter->default())) {
                            $end_time = $currentFilter->default();
                        }
                        continue;
                    }
                    $end_time = $value;
                }
            }

        } // manage default filter values (no filter changed on UI still)
        else {
            foreach ($filters as $filter) {
                $currentFilter = new $filter;

                if ($filter == StartTimeFilter::class && !empty($currentFilter->default())) {
                    $start_time = $currentFilter->default();
                }

                if ($filter == EndTimeFilter::class && !empty($currentFilter->default())) {
                    $end_time = $currentFilter->default();
                }
            }
        }

        if (!empty($start_time)) {
            $model = clone($model)->whereNot(
                function(Builder $query) use($start_time) {
                    $query->where('start_at', '<', $start_time)
                        ->where('end_at', '<', $start_time);
                });
        }

        if (!empty($end_time)) {
            $model = clone($model)->whereNot(
                function(Builder $query) use($end_time) {
                    $query->where('start_at', '>', Carbon::parse($end_time . ' 23:59:59'))
                        ->where('end_at', '>', Carbon::parse($end_time . ' 23:59:59'));
                });
        }

        return $model;
    }
}
