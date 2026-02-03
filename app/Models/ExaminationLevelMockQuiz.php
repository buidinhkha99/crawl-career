<?php

namespace App\Models;

use App\Scopes\ExaminationLevelMockQuizScope;
use App\Scopes\ExaminationMockQuizScope;

class ExaminationLevelMockQuiz extends ExaminationMockQuiz
{
    protected string $relationQuiz = QuizLevel::class;
    protected string $relationExam = ExamLevel::class;
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExaminationLevelMockQuizScope());
    }
}
