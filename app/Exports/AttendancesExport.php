<?php

namespace App\Exports;

use App\Models\Classroom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendancesExport implements FromCollection, WithMapping
{
    public function __construct(public Classroom $classroom)
    {
    }

    public function collection()
    {
        $userInClass = $this->classroom->attendees;
        $attendances = $this->classroom->attendances()->with('attendees')->get();
        $numberOfAttendances = $attendances->count();
        $data = $userInClass->map(function ($user, $key) use ($attendances, $numberOfAttendances) {
            $checkUserAttended =$attendances->map(function ($attendee) use ($user) {
                return [
                    'checked' => $attendee->attendees->where('user_id', $user->id)->whereNotNull('created_at')->isNotEmpty() ? 'x' : null
                ];
            });

            $percentAttended = $numberOfAttendances > 0 ? $checkUserAttended->where('checked', 'x')->count() / $numberOfAttendances  * 100 . '%'  : '0%';

            return [
                $key,
                $user->employee_code,
                $user->name,
                $user->dob->format('d/m/Y'),
                ...$checkUserAttended->pluck('checked')->values(),
                $percentAttended
            ];
        });

        return collect([
            ['Lớp huấn luyện ' . $this->classroom->name],
            [
                'STT',
                'Mã nv',
                'Họ tên',
                'Ngày sinh',
                ...$attendances->pluck('name'),
                'Chuyên cần'
            ],
            ...$data
        ]);
    }

    public function map($row): array
    {
        return $row;
    }
}
