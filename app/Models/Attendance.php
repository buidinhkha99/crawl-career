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

    public function classroom(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function attendees(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'attendance_classroom', 'attendance_id', 'user_id')->withTimestamps();
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
