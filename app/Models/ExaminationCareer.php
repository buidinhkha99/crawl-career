<?php

namespace App\Models;

use App\Scopes\ExaminationCareerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExaminationCareer extends BaseExamination
{
    protected string $relationQuiz = QuizCareer::class;
    protected string $relationExam = ExamCareer::class;
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExaminationCareerScope());
    }
}
