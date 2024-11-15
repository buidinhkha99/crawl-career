<?php

namespace App\Models;

use App\Scopes\ExaminationMockQuizScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExaminationMockQuiz extends BaseExamination
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExaminationMockQuizScope);
    }
}
