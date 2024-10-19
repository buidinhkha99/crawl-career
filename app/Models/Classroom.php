<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = ["name", "description", "lessons_count", "started_at", "ended_at", "start_attendance", "end_attendance"];

    protected $casts = [
        'started_at' => 'date',
        'ended_at' => 'date',
        'start_attendance' => 'datetime',
        'end_attendance' => 'datetime',
    ];

    public function attendees(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class)->using(ClassroomUser::class);
    }

    public function attendances(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
