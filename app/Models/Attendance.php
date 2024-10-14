<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ["classroom_id", "name", "description", "date"];

    protected $casts = [
        'date' => 'date',
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($attendance) {
            $attendees = $attendance->classroom->attendees;

            foreach ($attendees as $attendee) {
                AttendanceClassroom::create([
                    'attendance_id' => $attendance->id,
                    'user_id' => $attendee->id,
                    'created_at' => null,
                    'updated_at' => null,
                ]);
            }
        });
    }

    public function classroom(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function attendees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AttendanceClassroom::class, 'attendance_id', 'id');
    }

    public function getRegisterUrlAttribute()
    {
        return route('api.attendance.add', $this->id);
    }

    public function getQrCodeUrlAttribute()
    {
        return route('api.attendance.qr-code', $this->id);
    }
}
