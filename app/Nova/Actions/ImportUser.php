<?php

namespace App\Nova\Actions;

use App\Imports\UserImportCSV;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcelReader\SpreadsheetReader;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class ImportUser extends Action
{
    use InteractsWithQueue, Queueable;

    public User $user;

    public $onlyOnIndex = true;

    public $name = 'Thêm người lao động bằng file excel';

    public $confirmButtonText = 'Thêm';

    public $cancelButtonText = 'Đóng';

    /**
     * Perform the action on the given models.
     */
    public function handle(ActionFields $fields, Collection $models): Action|\Laravel\Nova\Actions\ActionResponse
    {
        $mime_types = collect(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
        $file_extension = collect(['xlsx','xls']);

        if ( $file_extension->filter(fn($file) => str_contains($fields->get('file')?->getClientOriginalExtension(), $file))->isEmpty() || ! $mime_types->contains($fields->get('file')?->getMimeType())) {
            return Action::danger(__('Incorrect file format .xlsx'));
        }

        Storage::disk('local')->put("file_user_example.xlsx", file_get_contents($fields->get('file')));

        $reader = new Xlsx();
        $spreadsheet = $reader->load(storage_path("app/file_user_example.xlsx"));
        $loadedSheetNames = $spreadsheet->getSheetNames();
        // check only one sheet
        if (count($loadedSheetNames) != 1) {
            return Action::danger(__('The excel file must have one sheet.'));
        }

        $writer = new Csv($spreadsheet);
        foreach($loadedSheetNames as $sheetIndex => $loadedSheetName) {
            $writer->setSheetIndex($sheetIndex);
            $writer->save(storage_path('app/file_user_example.csv'));
            Excel::import(new UserImportCSV(), storage_path('app/file_user_example.csv'));
        }

        return Action::message(__('Added the right user to the system, check the message to see if any user failed.'));
    }

    /**
     * Get the fields available on the action.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            File::make('File', 'file')->rules(['required'])->acceptedTypes('.xlsx'),
        ];
    }
}
