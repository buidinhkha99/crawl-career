<?php

namespace App\Scopes;

use App\Enums\ExaminationType;
use App\Enums\ScopeAccountType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ExaminationOccupationalScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('type', ExaminationType::Exam)
            ->where('scope_type', ScopeAccountType::OCCUPATIONAL);
    }
}
