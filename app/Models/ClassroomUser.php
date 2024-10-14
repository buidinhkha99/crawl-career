<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassroomUser extends Pivot
{
    public static function booted()
    {
        parent::booted();

        static::created(function ($classroomUser) {
            $attendances = $classroomUser->classroom->attendances;

            foreach ($attendances as $attendance) {
                AttendanceClassroom::create([
                    'attendance_id' => $attendance->id,
                    'user_id' => $classroomUser->user_id,
                    'created_at' => null,
                    'updated_at' => null,
                ]);
            }
        });

        static::deleted(function ($classroomUser) {
            $attendances = $classroomUser->classroom->attendances;

            foreach ($attendances as $attendance) {
                AttendanceClassroom::where('attendance_id', $attendance->id)->where('user_id', $classroomUser->user_id)->delete();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
