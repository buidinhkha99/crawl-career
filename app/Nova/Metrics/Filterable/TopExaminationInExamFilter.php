<?php

namespace App\Nova\Metrics\Filterable;

use App\Models\Exam;
use App\Nova\Filters\EndTimeFilter;
use App\Nova\Filters\StartTimeFilter;
use Coroowicaksono\ChartJsIntegration\BarChart;

class TopExaminationInExamFilter extends BarChart
{
    public function __construct($component = null)
    {
        parent::__construct($component);

        $exams = Exam::with('examinations')->where('start_at', '<=', now())->get();
        $value_tooltip = collect();
        $exams->each(function ($exam) use ($value_tooltip) {
            $value_tooltip->put($exam->name, $exam->name .' ('. $exam->start_at->format('H:i:s d/m/Y') .' - '. $exam->end_at->format('H:i:s d/m/Y').') ' . $exam->status );
        });

        $this->withMeta([
            'title' => __('Top Examination In Exam'),
            'model' => '\App\Models\Exam',
            'series' => null,
            'options' => (object)[
                'legend' => [
                    'display' => true,
                    'position' => 'left',
                ],
                'tooltips' => [
                    'callbacks' => [
                        'title' => "function(tooltipItem, data) {
                            var list = $value_tooltip;
                            return list[tooltipItem[0].label];
                        };",
                        'label' => "function(tooltipItem, data) {
                            return tooltipItem.dataset.label +': '+ tooltipItem.formattedValue +' ".__('examinations')."';
                        };"
                    ]
                ],
            ],
            'messageNoData' => __('There are no data that match the selected condition.'),
            'exp' => now()->addMinutes(5 ), // cache 5 minutes
            'filterable' => true,
            'label_column' => __('Number Examinations'),
            'filter' => [
                'type' => 'top_exam',
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
            ],
        ]);
    }
}
