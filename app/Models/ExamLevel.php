<?php

namespace App\Models;

use App\Scopes\ExamLevelScope;

class ExamLevel extends BaseExam
{
    protected $relationQuiz = QuizLevel::class;
    protected $relationExamination = ExaminationLevel::class;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExamLevelScope());
    }
}
