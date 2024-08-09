<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LessonQuestion extends Pivot
{
    protected $table = 'lesson_question';

    public function question(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function lesson(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
}
