<?php

namespace App\Nova\Actions;

use App\Exports\AttendancesExport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\Excel\Facades\Excel;
use Outl1ne\MultiselectField\Multiselect;

class DownloadAttendanceExcel extends Action
{
    public function name()
    {
        return __('Download Excel Attendance');
    }

    public function handle(ActionFields $fields, Collection $models): Action|\Laravel\Nova\Actions\ActionResponse
    {
        $classroom = $models->first();
        $path = 'diem_danh_lop_' . $classroom->name . '.xlsx';
        Excel::store((new AttendancesExport($classroom)), $path, 'public');
        $url = Storage::disk('public')->url($path);

        return Action::download($url, $path);
    }

    public function fields(NovaRequest $request)
    {
        return [];
    }
}
