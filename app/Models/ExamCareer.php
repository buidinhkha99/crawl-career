<?php

namespace App\Models;

use App\Scopes\ExamCareerScope;

class ExamCareer extends BaseExam
{
    protected $relationQuiz = QuizCareer::class;
    protected $relationExamination = ExaminationCareer::class;
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExamCareerScope());
    }
}
