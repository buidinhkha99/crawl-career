<?php

namespace App\Nova\Metrics\Filterable;

use App\Enums\ExaminationStatus;
use App\Models\Examination;
use App\Models\Quiz;
use App\Nova\Filters\EndTimeFilter;
use App\Nova\Filters\StartTimeFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class NumberSubmittedExaminationFilter extends Value
{
    use GlobalFilterable;
    public $icon = 'document-text';
    public function name()
    {
        return __('Number Submitted Examination');
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
        $model = $this->globalFiltered($request, Examination::class,[
            StartTimeFilter::class,
            EndTimeFilter::class
        ]);

        $number_examination = $model->select('id')->where('state', '!=',ExaminationStatus::NoExam)->count();
        if ($number_examination > 10000) {
            return $this->result($number_examination)
                ->suffix(__('examinations'))
                ->withoutSuffixInflection()
                ->format('0.00a');
        }

        return $this->result($number_examination)
            ->suffix(__('examinations'))
            ->withoutSuffixInflection()
            ->format('0,0');
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
}
