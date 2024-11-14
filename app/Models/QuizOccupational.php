<?php

namespace App\Models;

use App\Scopes\QuizOccupationalScope;
use App\Scopes\QuizScope;

class QuizOccupational extends BaseQuiz
{

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new QuizOccupationalScope());
    }
}
