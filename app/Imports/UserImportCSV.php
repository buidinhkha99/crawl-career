<?php

namespace App\Imports;

use App\Enums\UserGender;
use App\Models\User;
use App\Models\UserGroup;
use App\Rules\DoesntContainEmojis;
use App\Rules\FullnameRule;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Nova\Notifications\NovaNotification;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PHPUnit\Exception;

class UserImportCSV extends DefaultValueBinder implements ToCollection, WithCustomValueBinder
{
    public function bindValue(Cell $cell, $value)
    {
        // Column B is employee_code
        // We need it to be string
        if (is_numeric($value) && $cell->getColumn() === 'B') {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }
//
//        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    /**
     * @param  Collection  $collection
     */
    public function collection(Collection $rows)
    {
        // check rows have to have 11 fields
        if ($rows->count() <= 0 || $rows[0]->count() < 7) {
            Auth::user()->notify(
                NovaNotification::make()
                    ->message(__('The excel file does not have all the necessary data fields.'))
                    ->type('error')
            );

            return;
        }
        $rows = $rows->filter(fn ($value) => $value->filter()->isNotEmpty());
        // check duplicates
        $duplicates_rows = collect($rows)->duplicates();

        $duplicate_convert = $duplicates_rows->map(function ($value, $key) use ($rows) {
            return $rows?->where(0, $value[0])
                ->where(1, $value[1])
                ->where(2, $value[2])
                ->where(3, $value[3])
                ->where(4, $value[4])
                ->where(5, $value[5])
                ->where(6, $value[6])
//                ->where(7, $value[7])
//                ->where(8, $value[8])
//                ->where(9, $value[9])
//                ->where(10, $value[10])
                ?->forget($key)?->keys() ?? [];
        });

        $duplicate_convert->map(function ($row, $key) {
            Auth::user()->notify(
                NovaNotification::make()
                    ->message(__('Row :key add user error. Error: :error', [
                        'key' => $key + 1,
                        'error' => __('Duplicate data in rows :rows', [
                            'rows' => implode(', ', $row->map(fn ($id) => $id + 1)->toArray()),
                        ]),
                    ]))
                    ->type('error')
            );
        });

//        $duplicate_usernames = collect($rows)->duplicates(6);
//        $duplicate_usernames_convert = $duplicate_usernames->diffKeys($duplicates_rows)->map(function ($value, $key) use ($rows) {
//            return $rows?->where(5, $value)?->forget($key)?->keys() ?? [];
//        });
//        $duplicate_usernames_convert->map(function ($row, $key) use ($rows){
//            Auth::user()->notify(
//                NovaNotification::make()
//                    ->message(__('Row :key add user error. Error: :error', [
//                        'key' => $key + 1,
//                        'error' => __('Duplicate username in rows :rows', [
//                            'rows' => implode(', ', $row->map(fn ($id) => $id + 1)->toArray()),
//                        ]),
//                    ]))
//                    ->type('error')
//            );
//        });

        foreach ($rows as $key => $row) {
            if ($key === 0) {
                continue;
            }

            if ($row->count() < 7) {
                Auth::user()->notify(
                    NovaNotification::make()
                        ->message(__('Row :key add user error. Error: :error', [
                            'key' => $key + 1,
                            'error' => __('The excel file does not have all the necessary data fields.'),
                        ]))
                        ->type('error')
                );
                continue;
            }

//            if ($duplicate_usernames->contains($row[6])) {
//                continue;
//            }
//            $username = Arr::get(explode("'", $row[6]), 1, $row[6]);
//            $phone = Arr::get(explode("'", $row[5]), 1, $row[5]);
//            $phone = Str::remove(' ', $phone);
//
//            if (Str::contains($phone, '.'))
//            {
//                $phone = Str::replace('.', '', $phone);
//            }

            $employee_code = Arr::get(explode("'", $row[1]), 1, $row[1]);

            $validator = Validator::make(
                [
                    'group' => $row[0],
                    'employee_code' => $employee_code,
                    'name' => $row[2],
                    'dob' => $row[3],
//                    'gender' => $row[4],
//                    'phone' => $phone,
//                    'cccd/cmnd' => $username,
//                    'email' => $row[7],
                    'position' => $row[4],
                    'department' => $row[5],
                    'factory' => $row[6],
                ],
                [
                    'group' => 'nullable|max:100',
                    'employee_code' => ['required', 'max:50', new DoesntContainEmojis()],
                    'name' => ['required', 'max:50', new FullnameRule()],
                    'dob' => 'required',
//                    'gender' => 'required|in:'.implode(',', UserGender::getValues()),
//                    'phone' => ['nullable', 'regex:/((\+|)84|0[3|5|7|8|9])+([0-9]{8,9})\b/'],
//                    'cccd/cmnd' => 'required|numeric|digits_between:9,12',
//                    'email' => 'nullable|email|max:50',
                    'position' => 'nullable',
                    'department' => 'nullable',
                    'factory' => 'nullable',
                ],
                [
                    'before' => __('The :attribute field must be a day before the today.'),
                    'date_format' => __('The :attribute does not match the date format.'),
                ],
                [
                    'group' => __('Group User'),
                    'employee_code' => __('Employee Code'),
                    'name' => __('Name User'),
                    'dob' => __('Date Of Birth'),
//                    'gender' => __('Gender'),
//                    'phone' => __('Phone Number'),
//                    'cccd/cmnd' => 'CCCD/CMND',
//                    'email' => __('Email'),
                    'position' => __('Position'),
                    'department' => __('Department'),
                    'factory' => __('Factory'),
                ]
            );

            if ($validator->fails()) {
                Auth::user()->notify(
                    NovaNotification::make()
                        ->message(__('Row :key add user error. Error: :error', [
                            'key' => $key + 1,
                            'error' => $validator->errors()->first(),
                        ]))
                        ->type('error')
                );

                continue;
            }
            // check date
            $date = explode("/", $row[3]);
            if (count($date) != 3 || !checkdate($date[1], $date[0], $date[2])) {
                Auth::user()->notify(
                    NovaNotification::make()
                        ->message(__('Row :key add user error. Error: :error', [
                            'key' => $key + 1,
                            'error' => __('The Date of Birth field is not in the correct date/month/year format'),
                        ]))
                        ->type('error')
                );

                continue;
            }
            $dob = new \DateTime();
            $dob->setDate($date[2], $date[1], $date[0]);

//            // if username exists and employee code different
//            $userWithUsername = User::where('username', $username)->first();
//            if ($userWithUsername && $userWithUsername->employee_code != $employee_code) {
//                Auth::user()->notify(
//                    NovaNotification::make()
//                        ->message(__('Row :key add user error. Error: :error', [
//                            'key' => $key + 1,
//                            'error' => __('CCCD/CMND field already exists'),
//                        ]))
//                        ->type('error')
//                );
//
//                continue;
//            }

            // if employee code exists and username different
//            $userWithEmployeeCode = User::where('employee_code', $employee_code)->first();
//            if ($userWithUsername && $userWithUsername->employee_code != $employee_code)
//
//                Auth::user()->notify(
//                    NovaNotification::make()
//                        ->message(__('Row :key add user error. Error: :error', [
//                            'key' => $key + 1,
//                            'error' => __('Employee code field already exists'),
//                        ]))
//                        ->type('error')
//                );
//
//                continue;
//            }

            try {
                $user = User::updateOrCreate([
//                    'username' => $username,
                    'employee_code' => $employee_code,
                ], [
                    'name' => $row[2],
                    'dob' => $dob->format('d-m-Y'),
//                    'gender' => UserGender::fromValue($row[4])?->key,
//                    'phone' => $phone,
//                    'email' => $row[7],
                    'position' => $row[4],
                    'department' => $row[5],
                    'factory_name' => $row[6],
                ]);

                $list_group = explode( ',', $row[0]);
                foreach ($list_group as $group) {
                    $group = !empty($group) ? UserGroup::firstOrCreate([
                        'name' => $group,
                    ]) : null;

                    if (! $user->groups()->where('user_groups.id', $group?->id)->exists()) {
                        $user->groups()->attach($group?->id);
                    }
                }

            } catch (Exception $e) {
                Auth::user()->notify(
                    NovaNotification::make()
                        ->message(__('Row :key add user error. Error: :error', [
                            'key' => $key + 1,
                            'error' => $e->getMessage(),
                        ]))
                        ->type('error')
                );

                continue;
            }
        }
    }
}
