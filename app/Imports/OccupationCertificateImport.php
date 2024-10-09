<?php

namespace App\Imports;

use App\Enums\CertificateConstant;
use App\Jobs\CreateCertificate;
use App\Models\Certificate;
use App\Models\User;
use App\Rules\DoesntContainEmojis;
use App\Rules\FullnameRule;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Notifications\NovaNotification;
use Laravel\Octane\Exceptions\DdException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PHPUnit\Exception;

class OccupationCertificateImport extends CardImportCSV
{
    protected int $numberColumn = 8;
    protected $cardType = CertificateConstant::OCCUPATIONAL_SAFETY;

    /**
     * Handle with result
     * @param Collection $rows
     * @return bool
     * @throws DdException
     */
    public function save(Collection $rows): bool
    {
        $users = User::select(['id', 'employee_code', 'name'])->get();
        $certificates = Certificate::select('id', 'user_id', 'released_at', 'card_id')->where('type', $this->cardType)->get();
        // check duplicates card info
        $duplicateInfo = $rows->map(function ($row, $index) {
            $row['index'] = $index;
            return $row;
        })->whereNotNull(2)->groupBy(function ($row, $key) {
            if ($key == 0) {
                return;
            }
            // duplicate with card ID and year created
            return Carbon::createFromFormat('d/m/Y', $row[6])->year . '-' . $row[2];
        })->filter(function ($group) {
            return $group->count() > 1;
        });

        foreach ($rows as $key => $row) {
            if ($key === 0) {
                continue;
            }

            if ($row->count() < $this->numberColumn) {
                $this->notyError($key + 1, __('The excel file does not have all the necessary data fields.'));
                continue;
            }

            $checked = $this->validateData($row, $users, $key);
            if (!$checked) {
                continue;
            }

            $cardId = $row[2];
            $user = $users->where('employee_code', Arr::get(explode("'", $row[0]), 1, $row[0]))->where('name', trim($row[1]))->first();
            $complete_from = Carbon::createFromFormat('d/m/Y', $row[4]);
            $complete_to = Carbon::createFromFormat('d/m/Y', $row[5]);
            $released_at = Carbon::createFromFormat('d/m/Y', $row[6]);
            $effective_to = Carbon::createFromFormat('d/m/Y', $row[7]);

            $existDuplicate = $duplicateInfo->filter(function ($field, $keyDuplicate) use ($row, $released_at) {
                if (empty($cardId)) {
                    return false;
                }

                return $keyDuplicate == $released_at->year . '-' . $cardId;
            })->first();

            if ($existDuplicate) {
                $this->notyError($key + 1, __('Field duplicated card ID in same year at row: :row', [
                    'row' => $existDuplicate->where('index' , '!=', $key)->pluck('index')->map(fn($index) => $index + 1)->implode(',')
                ]));
                continue;
            }

            if (!empty($cardId)) {
                // same Year created
                $cards = $certificates->filter(function ($certificate) use ($released_at) {
                    return $certificate->released_at->year == $released_at->year;
                });
                // same card ID
                if ($other_card = $cards->where('user_id', '!=', $user->id)->where('card_id', $cardId)->first()) {
                    $this->notyError($key + 1, __('Card ID already exists in other user. Card ID. :card_id', [
                        'card_id' => $other_card->id
                    ]));
                    continue;
                }

                $cards = $cards->where('card_id', $cardId);
                $existed = $cards->where('user_id', $user->id);
                if ($existed->first()) {
                    $this->notyError($key + 1, __('Card already exists'));
                    continue;
                }
            }

            try {
                dispatch_sync(new CreateCertificate($user->id, $this->cardType, [
                    'description' => $row[3],
                    'complete_from' => $complete_from,
                    'complete_to' => $complete_to,
                    'released_at' => $released_at,
                    'effective_to' => $effective_to,
                    'card_id' => $cardId
                ]));
            } catch (Exception $e) {
                $this->notyError($key + 1, $e->getMessage());
            }

            $newCertificates = Certificate::select('id', 'user_id', 'released_at', 'card_id')
                ->whereNotIn('id', $certificates->pluck('id')->toArray())
                ->where('type', $this->cardType)
                ->get();
            $certificates->push(...$newCertificates);
        }

        return true;
    }

    /**
     * Validate data
     * @param $row
     * @param $users
     * @param $key
     * @return bool
     */
    public function validateData($row, $users, $key): bool
    {
        $employee_code = Arr::get(explode("'", $row[0]), 1, $row[0]);
        $name = $row[1];
        $validator = Validator::make(
            [
                'employee_code' => $employee_code,
                'name' => $name,
                'card_number' => $row[2],
                'description' => $row[3],
                'complete_from' => $row[4],
                'complete_to' => $row[5],
                'created_at' => $row[6],
                'effective_to' => $row[7],
            ],
            [
                'employee_code' => ['required', 'max:50', new DoesntContainEmojis()],
                'name' => ['required', 'max:50', new FullnameRule()],
                'card_number' => 'nullable|integer',
                'description' => 'string',
                'complete_from' => 'required|date_format:d/m/Y',
                'complete_to' => 'required|date_format:d/m/Y',
                'created_at' => 'required|date_format:d/m/Y',
                'effective_to' => 'required|date_format:d/m/Y',
            ],
            [
                'date_format' => __('The :attribute does not match the date format.'),
            ],
            [
                'employee_code' => __('Employee Code'),
                'name' => __('Name User'),
                'card_number' => __('Certificate ID'),
                'description' => __('Training course name'),
                'complete_from' => __('Training start date'),
                'complete_to' => __('Training end date'),
                'created_at' => __('Release date'),
                'effective_to' => __('Expiration date'),
            ]
        );

        if ($validator->fails()) {
            $this->notyError($key + 1, $validator->errors()->first());
            return false;
        }

        // check card info available
        if (!$users->where('employee_code', $employee_code)->where('name', $name)->first()) {
            $this->notyError($key + 1, __('Employee not found in the system'));
            return false;
        }

        return true;
    }
}
