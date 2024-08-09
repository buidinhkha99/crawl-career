<?php

namespace Coroowicaksono\ChartJsIntegration\Api;

use App\Enums\ExaminationStatus;
use App\Models\Question;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;

class TotalCircleController extends Controller
{
    use ValidatesRequests;

    /**
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(NovaRequest $request)
    {
        if ($request->input('model')) {
            $request->merge(['model' => urldecode($request->input('model'))]);
        }
        $showTotal = isset($request->options) ? json_decode($request->options, true)['showTotal'] ?? true : true;
        $advanceFilterSelected = isset($request->options) ? json_decode($request->options, true)['advanceFilterSelected'] ?? false : false;
        $dataForLast = isset($request->options) ? json_decode($request->options, true)['latestData'] ?? 3 : 3;
        $calculation = isset($request->options) ? json_decode($request->options, true)['sum'] ?? 1 : 1;
        $request->validate(['model' => ['bail', 'required', 'min:1', 'string']]);
        $model = $request->input('model');
        $modelInstance = new $model;
        $tableName = $modelInstance->getConnection()->getTablePrefix() . $modelInstance->getTable();
        $xAxisColumn = $request->input('col_xaxis') ?? DB::raw($tableName . '.created_at');

        $filters = collect([]);
        if ($request->get('filters')) {
            foreach ($request->get('filters') as $filter) {
                $option = json_decode($filter, true);
                $filters->push([
                    'class' => Arr::get($option, 'class'),
                    'current_value' => Arr::get($option, 'currentValue'),
                ]);
            }
        }

        $cacheKey = sprintf("chartjs:%s", hash('md4', $model . $filters->pluck('current_value')->join(', ')));
        $dataSet = Cache::get($cacheKey);
        if ($dataSet && $request->input('filterable')) {
            return response()->json($dataSet);
        }

        $labelList = [];
        $xAxis = [];
        $yAxis = [];
        $seriesSql = "";
        $defaultColor = array("#ffcc5c", "#91e8e1", "#ff6f69", "#88d8b0", "#b088d8", "#d8b088", "#88b0d8", "#6f69ff", "#7cb5ec", "#434348", "#90ed7d", "#8085e9", "#f7a35c", "#f15c80", "#e4d354", "#2b908f", "#f45b5b", "#91e8e1", "#E27D60", "#85DCB", "#E8A87C", "#C38D9E", "#41B3A3", "#67c4a7", "#992667", "#ff4040", "#ff7373", "#d2d2d2");
        if (isset($request->series)) {
            foreach ($request->series as $seriesKey => $serieslist) {
                $seriesData = json_decode($serieslist);
                $filter = $seriesData->filter;
                $labelList[$seriesKey] = $seriesData->label;
                if (empty($filter->value) && isset($filter->operator) && ($filter->operator == 'IS NULL' || $filter->operator == 'IS NOT NULL')) {
                    $seriesSql .= ", SUM(CASE WHEN " . $filter->key . " " . $filter->operator . " then " . $calculation . " else 0 end) as \"" . $labelList[$seriesKey] . "\"";
                } else if (empty($filter->value)) {
                    $seriesSql .= ", SUM(CASE WHEN ";
                    $countFilter = count($filter);
                    foreach ($filter as $keyFilter => $listFilter) {
                        $seriesSql .= " " . $listFilter->key . " " . ($listFilter->operator ?? "=") . " '" . $listFilter->value . "' ";
                        $seriesSql .= $countFilter - 1 != $keyFilter ? " AND " : "";
                    }
                    $seriesSql .= "then " . $calculation . " else 0 end) as \"" . $labelList[$seriesKey] . "\"";
                } else {
                    $seriesSql .= ", SUM(CASE WHEN " . $filter->key . " " . ($filter->operator ?? "=") . " '" . $filter->value . "' then " . $calculation . " else 0 end) as \"" . $labelList[$seriesKey] . "\"";
                }
            }
            
            if (isset($request->join)) {
                $joinInformation = json_decode($request->join, true);
                $query = $model::selectRaw('SUM(' . $calculation . ') counted' . $seriesSql)
                    ->join($joinInformation['joinTable'], $joinInformation['joinColumnFirst'], $joinInformation['joinEqual'], $joinInformation['joinColumnSecond']);
            } else {
                $query = $model::selectRaw('SUM(' . $calculation . ') counted' . $seriesSql);
            }

            if (is_numeric($advanceFilterSelected)) {
                $query->where($xAxisColumn, '>=', Carbon::now()->subDays($advanceFilterSelected));
            } else if ($advanceFilterSelected == 'YTD') {
                $query->whereBetween($xAxisColumn, [Carbon::now()->firstOfYear()->startOfDay(), Carbon::now()]);
            } else if ($advanceFilterSelected == 'QTD') {
                $query->whereBetween($xAxisColumn, [Carbon::now()->firstOfQuarter()->startOfDay(), Carbon::now()]);
            } else if ($advanceFilterSelected == 'MTD') {
                $query->whereBetween($xAxisColumn, [Carbon::now()->firstOfMonth()->startOfDay(), Carbon::now()]);
            } else if ($dataForLast != '*') {
                $query->where($xAxisColumn, '>=', Carbon::now()->firstOfMonth()->subMonth($dataForLast - 1));
            }

            if (isset(json_decode($request->options, true)['queryFilter'])) {
                $queryFilter = json_decode($request->options, true)['queryFilter'];
                foreach ($queryFilter as $qF) {
                    if (isset($qF['value']) && !is_array($qF['value'])) {
                        if (isset($qF['operator'])) {
                            $query->where($qF['key'], $qF['operator'], $qF['value']);
                        } else {
                            $query->where($qF['key'], $qF['value']);
                        }
                    } else {
                        if ($qF['operator'] == 'IS NULL') {
                            $query->whereNull($qF['key']);
                        } else if ($qF['operator'] == 'IS NOT NULL') {
                            $query->whereNotNull($qF['key']);
                        } else if ($qF['operator'] == 'IN') {
                            $query->whereIn($qF['key'], $qF['value']);
                        } else if ($qF['operator'] == 'NOT IN') {
                            $query->whereIn($qF['key'], $qF['value']);
                        } else if ($qF['operator'] == 'BETWEEN') {
                            $query->whereBetween($qF['key'], $qF['value']);
                        } else if ($qF['operator'] == 'NOT BETWEEN') {
                            $query->whereNotBetween($qF['key'], $qF['value']);
                        }
                    }
                }
            }

            if ($request->get('filterable') && $request->get('filter_options')) {
                $query = $this->filterGlobal($query, $request, $filters);
            }

            $dataSet = $query->get();
            $xAxis = collect($labelList);
            $countKey = 0;
            foreach ($request->series as $sKey => $sData) {
                $dataSeries = json_decode($sData);
                foreach ($dataSet as $dataDetail) {
                    $yAxis[0]['backgroundColor'][$sKey] = $dataSeries->backgroundColor ?? $defaultColor[$sKey];
                    $yAxis[0]['borderColor'][$sKey] = $dataSeries->borderColor ?? '#FFF';
                    $yAxis[0]['data'][$sKey] = $dataDetail[$dataSeries->label];
                }
                $countKey++;
            }
        } elseif ($request->input('type')) {
            $xAxis = collect([]);
            $dataSet = collect([]);
            $background_color = collect(["#ffcc5c", "#91e8e1", "#ff6f69", "#88d8b0", "#b088d8", "#d8b088"]);
            if ($request->input('type') == 'percent_user') {
                $query = $modelInstance::select(DB::raw('count(*) as count_user'), 'user_groups.name')
                    ->join('user_user_group', 'users.id', '=', 'user_user_group.user_id')
                    ->join('user_groups', 'user_user_group.user_group_id', '=', 'user_groups.id')
                    ->groupBy('user_groups.name')
                    ->orderBy('count_user', 'desc');

                if ($request->get('filterable') && $request->get('filter_options')) {
                    $query = $this->filterGlobal($query, $request, $filters);
                }

                $groups = $query->get();
                $groups->each(function ($group, $index) use ($dataSet, $background_color, $groups) {
                    if ($index >= 5 && $groups->count() > 6) {
                        $dataSet->push(
                            [
                                'label' => 'khác',
                                'value' => $groups->slice($index)->map(fn($g) => $g->count_user)->sum(),
                                'backgroundColor' => $background_color->get($index),
                                'borderColor' => 'transparent',
                            ]);
                        return false;
                    }

                    $dataSet->push([
                        'label' => $group->name,
                        'value' => $group->count_user,
                        'backgroundColor' => $background_color->get($index),
                        'borderColor' => 'transparent',
                    ]);
                });
            }

            if ($request->input('type') == 'percent_question') {
                $query = $modelInstance::select(DB::raw('count(*) as count_question'), 'topics.name')
                    ->join('topicables', function ($join) {
                        $join->on('questions.id', '=', 'topicables.topicable_id');
                        $join->where('topicables.topicable_type', '=', Question::class);
                    })
                    ->join('topics', 'topics.id', '=', 'topicables.topic_id')
                    ->groupBy('topics.name')
                    ->orderBy('count_question', 'desc');

                if ($request->get('filterable') && $request->get('filter_options')) {
                    $query = $this->filterGlobal($query, $request, $filters);
                }

                $topics = $query->get();

                $topics->each(function ($topic, $index) use ($dataSet, $background_color, &$total_others, $topics) {

                    if ($index >= 5 && $topics->count() > 6) {
                        $dataSet->push(
                            [
                                'label' => 'khác',
                                'value' => $topics->slice($index)->map(fn($g) => $g->count_question)->sum(),
                                'backgroundColor' => $background_color->get($index),
                                'borderColor' => 'transparent',
                            ]);
                        return false;
                    }

                    $dataSet->push([
                        'label' => $topic->name,
                        'value' => $topic->count_question,
                        'backgroundColor' => $background_color->get($index),
                        'borderColor' => 'transparent',
                    ]);
                });
            }

            if ($request->input('type') == 'percent_examination') {
                $background_color = [
                    "Đạt" => 'green',
                    "Không Đạt" => '#F5573B',
                    "Không thi" => '#FDE047',
                ];
                $query = $model::select('state', DB::raw('COUNT(state) AS state_count'))->groupBy('state');
                if ($request->get('filterable') && $request->get('filter_options')) {
                    $query = $this->filterGlobal($query, $request, $filters);
                }

                $states = $query->get();
                foreach ($states as $state) {

                    $dataSet->push([
                        'label' => $state->state,
                        'value' => $state->state_count,
                        'backgroundColor' => Arr::get($background_color, $state->state),
                        'borderColor' => 'transparent',
                    ]);
                }
            }


            $dataSet->each(function ($dataDetail, $sKey) use (&$yAxis, &$xAxis) {
                $yAxis[0]['backgroundColor'][$sKey] = Arr::get($dataDetail, 'backgroundColor', '#FFF');
                $yAxis[0]['borderColor'][$sKey] = Arr::get($dataDetail, 'borderColor', '#FFF');
                $yAxis[0]['data'][$sKey] = Arr::get($dataDetail, 'value');
                $xAxis->push(Arr::get($dataDetail, 'label'));
            });
        } else {
            throw new ThrowError('You need to have at least 1 series parameters for this type of chart. <br/>Check documentation: https://github.com/coroo/nova-chartjs');
        }
        if ($request->input('expires')) {
            Cache::put($cacheKey, ['dataset' => ['xAxis' => $xAxis, 'yAxis' => $yAxis]], Carbon::parse($request->input('expires')));
        }

        return response()->json(['dataset' => ['xAxis' => $xAxis, 'yAxis' => $yAxis]]);
    }

    protected function filterGlobal($query, $request, $filters)
    {
        foreach ($request->get('filter_options') as $filter_option) {
            $option = json_decode($filter_option, true);

            $filter = $filters->where('class', Arr::get($option, 'filter'))->first();

            if (!empty($filter)) {

                if (Arr::get($filter, 'class') == "App\Nova\Filters\EndTimeFilter") {
                    $query->where(Arr::get($option, 'column'), Arr::get($option, 'operator'), Carbon::parse(Arr::get($filter, 'current_value'))->addDays(1));
                }

                if (Arr::get($filter, 'class') != "App\Nova\Filters\EndTimeFilter") {
                    $query->where(Arr::get($option, 'column'), Arr::get($option, 'operator'), Carbon::parse(Arr::get($filter, 'current_value')));
                }
            }

            // need to apply default filters if they are not changed on UI
            if (empty($filter)) {
                $currentFilter = new (Arr::get($option, 'filter'));

                if (!empty($currentFilter->default())) {


                    if (Arr::get($option, 'filter') == "App\Nova\Filters\EndTimeFilter") {
                        $query->where(Arr::get($option, 'column'), Arr::get($option, 'operator'), Carbon::parse($currentFilter->default() . ' 23:59:59'));
                    }

                    if (Arr::get($option, 'filter') != "App\Nova\Filters\EndTimeFilter") {
                        $query->where(Arr::get($option, 'column'), Arr::get($option, 'operator'), Carbon::parse($currentFilter->default()));
                    }
                }
            }
        }
        return $query;
    }
}
