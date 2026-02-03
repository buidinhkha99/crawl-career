<?php

namespace App\Models;

use App\Enums\ScopeAccountType;
use App\Scopes\ExamLevelScope;

class ExamLevel extends BaseExam
{
    protected $relationQuiz = QuizLevel::class;
    protected $relationExamination = ExaminationLevel::class;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExamLevelScope());
        static::saving(function ($model) {
            $model->scope_type = ScopeAccountType::LEVEL;
        });
    }
}
