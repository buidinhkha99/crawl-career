<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;

class ExaminationCareerPolicy extends BasePolicy
{
    public $key = 'ExaminationCareer';

    public function create(Model $user): bool
    {
        return false;
    }

    public function update(Model $user, $model): bool
    {
        return false;
    }
}
