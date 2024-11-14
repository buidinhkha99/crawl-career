<?php

namespace App\Models;

use App\Scopes\ExaminationCareerMockQuizScope;

class ExaminationCareerMockQuiz extends ExaminationMockQuiz
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExaminationCareerMockQuizScope());
    }
}
