<?php

namespace App\Nova\Actions;

use App\Exports\ExaminationsExport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\Excel\Facades\Excel;
use Outl1ne\MultiselectField\Multiselect;

class DownloadExcel extends Action
{
    public function name()
    {
        return __('Download Excel Report');
    }

    public function handle(ActionFields $fields, Collection $models): Action|\Laravel\Nova\Actions\ActionResponse
    {
        Excel::store((new ExaminationsExport())->setIds($models->pluck('id'))->setHeadings(collect(json_decode($fields->field_export))), 'bao_cao_sat_hach.xlsx', 'public');
        $url = Storage::disk('public')->url('bao_cao_sat_hach.xlsx');

        return Action::download($url, 'bao_cao_sat_hach.xlsx');
    }

    public function fields(NovaRequest $request)
    {
        return [
            Multiselect::make(__('Fields export'), 'field_export')
                ->options([
                    __('Employee Code') => __('Employee Code'),
                    __('Name') => __('Name'),
                    __('Date Of Birth') => __('Date Of Birth'),
                    __('CCCD/CMND') => __('CCCD/CMND'),
                    __('Exam') => __('Exam'),
                    __('Quiz') => __('Quiz'),
                    __('Score') => __('Score'),
                    __('Result') => __('Result'),
                    __('Duration') => __('Duration'),
                    __('Exam date') => __('Exam date'),
                    __('Gender') => __('Gender'),
                    __('Position ') => __('Position '),
                    __('Department') => __('Department'),
                    __('Factory') => __('Factory'),
                    __('Start Time') => __('Start Time'),
                    __('End Time') => __('End Time'),
                    __('Number Correct Answer') => __('Number Correct Answer'),
                    __('Number wrong answer') => __('Number wrong answer'),
                    __('Number Unanswered') => __('Number Unanswered'),
                ])
                ->default(fn () => [
                    __('Employee Code'),
                    __('Name'),
                    __('Date Of Birth'),
                    __('CCCD/CMND'),
                    __('Exam'),
                    __('Quiz'),
                    __('Score'),
                    __('Result'),
                    __('Duration'),
                    __('Exam date'),
                ])
                ->rules('required'),
        ];
    }
}
