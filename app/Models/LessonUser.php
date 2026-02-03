<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LessonUser extends Pivot
{
    protected $table = 'lesson_user';

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
