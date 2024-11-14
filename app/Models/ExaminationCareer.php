<?php

namespace App\Models;

use App\Scopes\ExaminationCareerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExaminationCareer extends Examination
{
    protected $table = 'examinations';

    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExaminationCareerScope());
    }
}
