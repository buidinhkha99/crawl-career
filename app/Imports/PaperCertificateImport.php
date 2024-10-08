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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Octane\Exceptions\DdException;
use PHPUnit\Exception;

class PaperCertificateImport extends CardImportCSV
{
    protected int $numberColumn = 13;
    protected $cardType = CertificateConstant::PAPER_SAFETY;

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
            if ($key == 0 || empty($row[2])) {
                return;
            }

            // duplicate with card ID and year created
            return Carbon::createFromFormat('d/m/Y', $row[11])->year . '-' . $row[2];
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

            $employee_code = Arr::get(explode("'", $row[0]), 1, $row[0]);
            $name = $row[1];
            $cardId = $row[2];
            $gender = ucfirst($row[3]);
            $dob = Carbon::createFromFormat('d/m/Y', $row[4]);
            $nationality = $row[5];
            $cccd = Arr::get(explode("'", $row[6]), 1, $row[6]);
            $group = $row[7];
            $result = ucfirst($row[8]);
            $start_date = Carbon::createFromFormat('d/m/Y', $row[9]);
            $end_date = Carbon::createFromFormat('d/m/Y', $row[10]);
            $released_at = Carbon::createFromFormat('d/m/Y', $row[11]);
            $effective_from = Carbon::createFromFormat('d/m/Y', $row[12]);
            $effective_to = Carbon::createFromFormat('d/m/Y', $row[13]);
            $user = $users->where('employee_code', $employee_code)->where('name', trim($name))->first();
            $existDuplicate = $duplicateInfo->filter(function ($field, $keyDuplicate) use ($row, $released_at, $cardId) {
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

                $cards = $cards->where('card_id', $cardId);
                $existed = $cards->where('user_id', $user->id);
                if ($existed->first()) {
                    $this->notyError($key + 1, __('Card already exists'));
                    continue;
                }
            }

            try {
                dispatch_sync(new CreateCertificate($user->id, $this->cardType, [
                    'gender' => $gender,
                    'released_at' => $released_at,
                    'card_id' => $cardId,
                    'employee_code' => $employee_code,
                    'name' => $name,
                    'dob' => $dob,
                    'nationality' => $nationality,
                    'cccd' => $cccd,
                    'group' => $group,
                    'result' => $result,
                    'complete_from' => $start_date,
                    'complete_to' => $end_date,
                    'effective_from' => $effective_from,
                    'effective_to' => $effective_to,
                ]));
            } catch (Exception $e) {
                $this->notyError($key + 1, $e->getMessage());
            }
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
                'gender' => $row[3],
                'dob' => $row[4],
                'nationality' => $row[5],
                'cccd' => Arr::get(explode("'", $row[6]), 1, $row[6]),
                'group' => $row[7],
                'result' => $row[8],
                'start_date' => $row[9],
                'end_date' => $row[10],
                'released_at' => $row[11],
                'effective_from' => $row[12],
                'effective_to' => $row[13],
            ],
            [
                'employee_code' => ['required', 'max:50', new DoesntContainEmojis()],
                'name' => ['required', 'max:50', new FullnameRule()],
                'card_number' => 'nullable|integer',
                'gender' => ['required','string', Rule::in(['Nam','Nữ','Khác', 'nam','nữ','khác'])],
                'dob' => 'required|date_format:d/m/Y',
                'nationality' => 'required|string',
                'cccd' => 'required|numeric|digits_between:9,12',
                'group' => 'required|string',
                'result' => ['required','string', Rule::in(['Giỏi','Khá','Trung bình', 'giỏi','khá','trung bình'])],
                'start_date' => 'required|date_format:d/m/Y',
                'end_date' => 'required|date_format:d/m/Y',
                'released_at' => 'required|date_format:d/m/Y',
                'effective_from' => 'required|date_format:d/m/Y',
                'effective_to' => 'required|date_format:d/m/Y',
            ],
            [
                'date_format' => __('The :attribute does not match the date format.'),
            ],
            [
                'employee_code' => __('Employee Code'),
                'name' => __('Name User'),
                'card_number' => __('Certificate ID'),
                'created_at' => __('Release date'),
                'gender' => __('Gender'),
                'dob' => __('Date Of Birth'),
                'nationality' => __('Nationality'),
                'cccd' => 'CCCD/CMND',
                'group' => __('Group User'),
                'result' => __('Result training'),
                'start_date' => __('Training start date'),
                'end_date' => __('Training end date'),
                'released_at' => __('Release date'),
                'effective_from' => __('Expiration from'),
                'effective_to' => __('Expiration to'),
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
