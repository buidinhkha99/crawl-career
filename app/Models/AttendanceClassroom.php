<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AttendanceClassroom extends Pivot
{
    protected $with = ['user', 'attendance'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
