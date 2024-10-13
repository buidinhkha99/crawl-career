<?php

namespace App\Nova\Metrics;

use App\Enums\ExaminationStatus;
use App\Models\QuizAttempt;
use Coroowicaksono\ChartJsIntegration\PieChart;

class NumberDoMockQuiz extends PieChart
{
    public function __construct($component = null)
    {
        parent::__construct($component);

        $this->withMeta([
            'title' => __('Percentage of test passes/failures'),
            'filterable' => false,
            'series' => array([
                'data' => [
                    QuizAttempt::where('is_pass', true)->count(),
                    QuizAttempt::where('is_pass', false)->count(),
                ],
                'backgroundColor' => ["#88d8b0", "#ff6f69"],
                'borderColor' => 'transparent',
            ]),
            'options' => (object)[
                'xaxis' => [
                    'categories' => [ExaminationStatus::Pass, ExaminationStatus::Fail]
                ],
                'tooltips' => [
                    'enabled' => true,
                    'callbacks' => [
                        'label' => "function(tooltipItem, data) {
                            var label = tooltipItem.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += tooltipItem.parsed || 0;
                            label += ' đề thi thử (' + Math.round(tooltipItem.parsed * 100 / Object.values(tooltipItem.dataset.data).reduce((a, b) => a + b, 0)) + ' %)';
                            return label;
                        };"
                    ],
                ],
            ],
        ]);
    }
}
