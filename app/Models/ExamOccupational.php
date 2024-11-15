<?php

namespace App\Models;

use App\Scopes\ExamScope;

class ExamOccupational extends BaseExam
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExamScope());
    }
}
