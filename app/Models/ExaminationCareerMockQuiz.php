<?php

namespace App\Models;

use App\Scopes\ExaminationCareerMockQuizScope;

class ExaminationCareerMockQuiz extends ExaminationMockQuiz
{
    protected string $relationQuiz = QuizCareer::class;
    protected string $relationExam = ExamCareer::class;
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExaminationCareerMockQuizScope());
    }
}
