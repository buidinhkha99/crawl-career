<?php

namespace App\Nova\Metrics\Filterable;

use App\Enums\ExaminationStatus;
use App\Nova\Filters\EndTimeFilter;
use App\Nova\Filters\StartTimeFilter;
use Coroowicaksono\ChartJsIntegration\PieChart;

class PercentExaminationsFilter extends PieChart
{
    public function __construct($component = null)
    {
        parent::__construct($component);

        $this->withMeta([
            'title' => __('Percentage of exams submitted by results'),
            'model' => '\App\Models\Examination',
            'series' => null,
            'options' => (object)[
                'btnFilter' => false,
                'showPercentage' => true,
            ],
            'messageNoData' => __('There are no data that match the selected condition.'),
            'exp' => now()->addMinutes(5),
            'filterable' => true,
            'filter' => [
                'type' => 'percent_examination',
                'filterClass' => [
                    [
                        'filter' => StartTimeFilter::class,
                        'column' => 'created_at',
                        'operator' => '>='
                    ],
                    [
                        'filter' => EndTimeFilter::class,
                        'column' => 'created_at',
                        'operator' => '<='
                    ]
                ],
            ]
        ]);
    }
}
