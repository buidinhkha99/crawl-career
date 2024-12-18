<?php

namespace App\Models;

use App\Enums\ScopeAccountType;
use App\Scopes\QuizLevelScope;

class QuizLevel extends BaseQuiz
{
    protected $relationExam = ExamLevel::class;
    protected $relationExamination = ExaminationLevel::class;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new QuizLevelScope());
        static::saving(function ($model) {
            $model->scope_type = ScopeAccountType::LEVEL;
        });
    }
}
