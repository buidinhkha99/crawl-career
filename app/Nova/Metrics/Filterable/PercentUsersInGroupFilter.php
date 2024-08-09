<?php

namespace App\Nova\Metrics\Filterable;

use App\Models\UserGroup;
use App\Nova\Filters\EndTimeFilter;
use App\Nova\Filters\StartTimeFilter;
use Coroowicaksono\ChartJsIntegration\PieChart;

class PercentUsersInGroupFilter extends PieChart
{
    public function __construct($component = null)
    {
        parent::__construct($component);

        $categories = collect([]);
        $groups = UserGroup::select('id', 'name')->withCount('users')->orderBy('users_count', 'desc')->get();

        $groups->each(function ($group, $index) use ($categories, $groups) {
            $categories->push($group->getAttribute('name'));
        });

        $this->withMeta([
            'title' => __('Percent New Users In Groups'),
            'model' => '\App\Models\User',
            'series' => null,
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
            'messageNoData' => __('There are no data that match the selected condition.'),
            'exp' => now()->addMinutes(5 ), // cache 5 minutes
            'filterable' => true,
            'filter' => [
                'type' => 'percent_user',
                'filterClass' => [
                    [
                        'filter' => StartTimeFilter::class,
                        'column' => 'users.created_at',
                        'operator' => '>='
                    ],
                    [
                        'filter' => EndTimeFilter::class,
                        'column' => 'users.created_at',
                        'operator' => '<='
                    ]
                ],
            ],
        ]);
    }
}
