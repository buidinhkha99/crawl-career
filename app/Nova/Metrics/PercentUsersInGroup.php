<?php

namespace App\Nova\Metrics;

use App\Enums\ExaminationStatus;
use App\Models\Examination;
use App\Models\Topic;
use App\Models\UserGroup;
use Coroowicaksono\ChartJsIntegration\PieChart;

class PercentUsersInGroup extends PieChart
{
    public function __construct($component = null)
    {
        parent::__construct($component);

        $data = collect([]);
        $categories = collect([]);
        // default color for 6 topics
        $background_color = collect(["#ffcc5c", "#91e8e1", "#ff6f69", "#88d8b0", "#b088d8", "#d8b088"]);
        $groups = UserGroup::select('id', 'name')->withCount('users')->orderBy('users_count', 'desc')->get();

        $groups->each(function ($group, $index) use ($data, $categories, $background_color, $groups) {
            if ($index >= 5 && $groups->count() > 6) {
                $data->push($groups->slice($index)->map(fn($g) => $g->users_count)->sum());
                $categories->push('khác');
                return false;
            }

            $data->push($group->users_count);
            $categories->push($group->getAttribute('name'));
        });

        $this->withMeta([
            'title' => __('Percent Users In Groups'),
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
                            label += ' người (' + Math.round(tooltipItem.parsed * 100 / Object.values(tooltipItem.dataset.data).reduce((a, b) => a + b, 0)) + ' %)';
                            return label;
                        };"
                    ],
                ],
            ],
        ]);
    }
}
