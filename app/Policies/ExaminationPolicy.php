<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;

class ExaminationPolicy extends BasePolicy
{
    public $key = 'examination';

    public function create(Model $user): bool
    {
        return false;
    }

    public function update(Model $user, $model): bool
    {
        return false;
    }
}
