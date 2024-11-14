<?php

namespace App\Models;

use App\Scopes\QuizLevelScope;

class QuizLevel extends BaseQuiz
{
    protected $relationExam = ExamLevel::class;
    protected $relationExamination = ExaminationLevel::class;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new QuizLevelScope());
    }
}
