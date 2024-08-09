<?php

namespace App\Nova\Metrics\Filterable;

use App\Models\Topic;
use App\Nova\Filters\EndTimeFilter;
use App\Nova\Filters\StartTimeFilter;
use Coroowicaksono\ChartJsIntegration\PieChart;

class PercentQuestionsInTopicFilter extends PieChart
{
    public function __construct($component = null)
    {
        parent::__construct($component);

        $data = collect([]);
        $categories = collect([]);
        // default color for 6 topics
        $background_color = collect(["#ffcc5c", "#91e8e1", "#ff6f69", "#88d8b0", "#b088d8", "#d8b088"]);
        $total_others = 0;
        $topics = Topic::select('id', 'name')->withCount('questions')->orderBy('questions_count', 'desc')->get();

        $topics->each(function ($topic, $index) use ($data, $categories, $background_color, &$total_others, $topics) {
            if ($index >= 6 && $topics->count() > 6) {
                $total_others += $topic->getAttribute('questions')->count();
            }

            if ($index == $topics->count() - 1 && $topics->count() > 6) {
                $data->push($total_others);
                $categories->push('khác');
            }

            if ($index <= 4) {
                $data->push($topic->getAttribute('questions')->count());
                $categories->push($topic->getAttribute('name'));
            }
        });

        $this->withMeta([
            'title' => __('Percent New Questions In Topic'),
            'model' => '\App\Models\Question',
            'series' => null,
            'options' => (object)[
                'tooltips' => [
                    'enabled' => true,
                    'callbacks' => [
                        'label' => "function(tooltipItem, data) {
                            var label = tooltipItem.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += tooltipItem.parsed || 0;
                            label += ' câu (' + Math.round(tooltipItem.parsed * 100 / Object.values(tooltipItem.dataset.data).reduce((a, b) => a + b, 0)) + ' %)';
                            return label;
                        };"
                    ],
                ],
            ],
            'messageNoData' => __('There are no data that match the selected condition.'),
            'exp' => now()->addMinutes(5 ), // cache 5 minutes
            'filterable' => true,
            'filter' => [
                'type' => 'percent_question',
                'filterClass' => [
                    [
                        'filter' => StartTimeFilter::class,
                        'column' => 'questions.created_at',
                        'operator' => '>='
                    ],
                    [
                        'filter' => EndTimeFilter::class,
                        'column' => 'questions.created_at',
                        'operator' => '<='
                    ]
                ],
            ],
        ]);
    }
}
