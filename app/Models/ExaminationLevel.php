<?php

namespace App\Models;

use App\Scopes\ExaminationLevelScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExaminationLevel extends BaseExamination
{
    use HasFactory;

    protected string $relationQuiz = QuizLevel::class;
    protected string $relationExam = ExamLevel::class;
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExaminationLevelScope());
    }

}
