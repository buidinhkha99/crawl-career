<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Notifications\NovaNotification;
use Laravel\Octane\Exceptions\DdException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

abstract class CardImportCSV extends DefaultValueBinder implements ToCollection, WithCustomValueBinder
{
    protected int $numberColumn = 0;
    protected $cardType = null;

    public function bindValue(Cell $cell, $value)
    {
        // Column A is employee_code
        // Have to be string
        if (is_numeric($value) && $cell->getColumn() === 'A') {
            $cell->setValueExplicit($value);

            return true;
        }
        return parent::bindValue($cell, $value);
    }

    /**
     * @param Collection $rows
     * @throws DdException
     */
    public function collection(Collection $rows)
    {
        // check rows have to have enough fields
        if ($rows->count() <= 0 || $rows[0]->count() < $this->numberColumn) {
            $this->notyError(null, __('The excel file does not have all the necessary data fields.'));
            return;
        }

        $rows = $rows->filter(fn($value) => $value->filter()->isNotEmpty());
        $this->checkDuplicates($rows);
        $this->save($rows);
    }

    public function notyError($key = null, $errorMessage = null): void
    {
        if (empty($key)) {
            Auth::user()->notify(
                NovaNotification::make()
                    ->message($errorMessage)
                    ->type('error')
            );

            return;
        }

        Auth::user()->notify(
            NovaNotification::make()
                ->message(__('Row :key add user error. Error: :error', [
                    'key' => $key,
                    'error' => $errorMessage,
                ]))
                ->type('error')
        );
    }

    /**
     * Check duplicate information
     * @param Collection $rows
     * @return void
     */
    public function checkDuplicates(Collection $rows): void
    {
        $duplicates_rows = collect($rows)->duplicates();
        $duplicate_convert = $duplicates_rows->map(function ($value, $key) use ($rows) {
            $rows = $rows?->where(0, $value[0]);
            for ($i = 0; $i < $this->numberColumn; $i++) {
                $rows = $rows->where(1, $value[$i]);
            }

            return $rows?->forget($key)?->keys() ?? [];
        });

        $duplicate_convert->map(function ($row, $key) {
            $this->notyError($key + 1,
                __('Duplicate data in rows :rows', [
                    'rows' => implode(', ', $row->map(fn($id) => $id + 1)->toArray()),
                ]));
        });
    }

    /**
     * Handle with result
     * @param Collection $rows
     * @return bool
     * @throws DdException
     */
    abstract public function save(Collection $rows): bool;
}
