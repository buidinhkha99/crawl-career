<?php

namespace App\Models;

use App\Scopes\QuizCareerScope;

class QuizCareer extends BaseQuiz
{
    protected $relationExam = ExamCareer::class;
    protected $relationExamination = ExaminationCareer::class;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new QuizCareerScope());
    }
}
