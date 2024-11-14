<?php

namespace App\Scopes;

use App\Enums\QuizType;
use App\Enums\ScopeAccountType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class QuizLevelScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('type', QuizType::Exam)->whereHas('exam', function ($query) {
            $query->where('scope_type', ScopeAccountType::LEVEL);
        });
    }
}
