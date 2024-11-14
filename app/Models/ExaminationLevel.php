<?php

namespace App\Models;

use App\Scopes\ExaminationLevelScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExaminationLevel extends Examination
{
    protected $table = 'examinations';

    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExaminationLevelScope());
    }

}
