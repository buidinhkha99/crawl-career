<?php

namespace App\Nova\Metrics;

use App\Enums\ExaminationStatus;
use App\Nova\Filters\EndTimeFilter;
use App\Nova\Filters\StartTimeFilter;
use Coroowicaksono\ChartJsIntegration\PieChart;

class PercentExaminations extends PieChart
{
    public function __construct($component = null)
    {
        parent::__construct($component);

        $this->withMeta([
            'title' => __('Percent Examinations'),
            'model' => '\App\Models\Examination',
            'filterable' => false,
            'start_time_default' => now()->addDays(7),
            'end_time_default' => now(),
            'series' => array(
                [
                    'label' => ExaminationStatus::Pass,
                    'backgroundColor' => 'green',
                    'filter' => [
                        'key' => 'state', // State Column for Count Calculation Here
                        'value' => ExaminationStatus::Pass
                    ],
                    'borderColor' => 'transparent',
                ],
                [
                    'label' => ExaminationStatus::Fail,
                    'backgroundColor' => '#F5573B',
                    'filter' => [
                        'key' => 'state', // State Column for Count Calculation Here
                        'value' => ExaminationStatus::Fail
                    ],
                    'borderColor' => 'transparent',
                ],
                [
                    'label' => ExaminationStatus::NoExam,
                    'backgroundColor' => '#FDE047',
                    'filter' => [
                        'key' => 'state', // State Column for Count Calculation Here
                        'value' => ExaminationStatus::NoExam
                    ],
                    'borderColor' => 'transparent',
                ]
            ),
            'options' => (object)[
                'btnFilter' => false,
                'showPercentage' => true,
            ],
        ]);
    }
}
