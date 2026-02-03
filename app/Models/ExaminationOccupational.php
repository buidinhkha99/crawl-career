<?php

namespace App\Models;

use App\Scopes\ExaminationOccupationalScope;
use App\Scopes\ExaminationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExaminationOccupational extends BaseExamination
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExaminationOccupationalScope());
    }
}
