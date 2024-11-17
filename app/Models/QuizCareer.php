<?php

namespace App\Models;

use App\Enums\ScopeAccountType;
use App\Scopes\QuizCareerScope;

class QuizCareer extends BaseQuiz
{
    protected $relationExam = ExamCareer::class;
    protected $relationExamination = ExaminationCareer::class;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new QuizCareerScope());

        static::saving(function ($model) {
            $model->scope_type = ScopeAccountType::CAREER;
        });
    }
}
