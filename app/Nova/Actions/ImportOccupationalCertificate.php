<?php

namespace App\Nova\Actions;

use App\Enums\CertificateConstant;
use App\Imports\CardImportCSV;
use App\Imports\UserImportCSV;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class ImportOccupationalCertificate extends Action
{
    public User $user;
    public $onlyOnIndex = true;
    public $name = 'Thêm thẻ ATLD bằng file excel';
    public $confirmButtonText = 'Thêm';
    public $cancelButtonText = 'Đóng';
    public string $type;

    /**
     * @throws Exception
     */
    public function __construct(string $type)
    {
        if (!in_array($type, CertificateConstant::LIST_TYPE_CERTIFICATE))
        {
            throw new Exception('Type not supported');
        }

        $this->type = $type;
    }

    /**
     * Perform the action on the given models.
     */
    public function handle(ActionFields $fields, Collection $models): Action|ActionResponse
    {
        $mime_types = collect(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
        $file_extension = collect(['xlsx', 'xls']);

        if ($file_extension->filter(fn($file) => str_contains($fields->get('file')?->getClientOriginalExtension(), $file))->isEmpty() || !$mime_types->contains($fields->get('file')?->getMimeType())) {
            return Action::danger(__('Incorrect file format .xlsx'));
        }

        $nameFile = 'file_certificate_' . $this->type . '_example.xlsx';
        Storage::disk('local')->put($nameFile, file_get_contents($fields->get('file')));

        $reader = new Xlsx();
        $spreadsheet = $reader->load(storage_path("app/$nameFile"));
        $loadedSheetNames = $spreadsheet->getSheetNames();
        // check only one sheet
        if (count($loadedSheetNames) != 1) {
            return Action::danger(__('The excel file must have one sheet.'));
        }

        $writer = new Csv($spreadsheet);
        foreach ($loadedSheetNames as $sheetIndex => $loadedSheetName) {
            $writer->setSheetIndex($sheetIndex);
            $writer->save(storage_path('app/file_certificate_example.csv'));
            Excel::import(new CardImportCSV(), storage_path('app/file_certificate_example.csv'));
        }

        return Action::message(__('Added list cards, check the message to see if any user failed.'));
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
