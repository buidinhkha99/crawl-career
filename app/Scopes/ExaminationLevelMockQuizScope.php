<?php

namespace App\Scopes;

use App\Enums\ExaminationType;
use App\Enums\ScopeAccountType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ExaminationLevelMockQuizScope implements Scope
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
        $builder->where('type', ExaminationType::Random)
            ->whereNotNull('user_id')
            ->where('scope_type', ScopeAccountType::LEVEL);
    }
}
