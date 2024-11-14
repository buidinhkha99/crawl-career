<?php

namespace App\Models;

use App\Scopes\ExaminationLevelMockQuizScope;
use App\Scopes\ExaminationMockQuizScope;

class ExaminationLevelMockQuiz extends ExaminationMockQuiz
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExaminationLevelMockQuizScope());
    }
}
