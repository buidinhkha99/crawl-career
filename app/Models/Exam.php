<?php

namespace App\Models;

use App\Scopes\ExamScope;

class Exam extends BaseExam
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExamScope());
    }
}
