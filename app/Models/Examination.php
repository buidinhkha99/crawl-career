<?php

namespace App\Models;

use App\Scopes\ExaminationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examination extends BaseExamination
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExaminationScope);
    }
}
