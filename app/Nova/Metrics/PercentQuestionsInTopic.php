<?php

namespace App\Nova\Metrics;

use App\Models\Topic;
use Coroowicaksono\ChartJsIntegration\PieChart;

class PercentQuestionsInTopic extends PieChart
{
    public function __construct($component = null)
    {
        parent::__construct($component);

        $data = collect([]);
        $categories = collect([]);
        // default color for 6 topics
        $background_color = collect(["#ffcc5c", "#91e8e1", "#ff6f69", "#88d8b0", "#b088d8", "#d8b088"]);
        $topics = Topic::select('id', 'name')->withCount('questions')->orderBy('questions_count', 'desc')->get();

        $topics->each(function ($topic, $index) use ($data, $categories, $background_color, $topics) {
            if ($index >= 5 && $topics->count() > 6) {
                $data->push($topics->slice($index)->map(fn($g) => $g->questions_count)->sum());
                $categories->push('khác');
                return false;
            }

            $data->push($topic->questions_count);
            $categories->push($topic->getAttribute('name'));
        });

        $this->withMeta([
            'title' => __('Percent Questions In Topic'),
            'filterable' => false,
            'series' => array([
                'data' => $data->toArray(),
                'backgroundColor' => $background_color,
                'borderColor' => 'transparent',
            ]),
            'options' => (object)[
                'xaxis' => [
                    'categories' => $categories->toArray()
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
                            label += ' câu (' + Math.round(tooltipItem.parsed * 100 / Object.values(tooltipItem.dataset.data).reduce((a, b) => a + b, 0)) + ' %)';
                            return label;
                        };"
                    ],
                ],
            ],
        ]);
    }
}
