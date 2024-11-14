<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;

class ExaminationLevelPolicy extends BasePolicy
{
    public $key = 'ExaminationLevel';

    public function create(Model $user): bool
    {
        return false;
    }

    public function update(Model $user, $model): bool
    {
        return false;
    }
}
