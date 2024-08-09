<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;

class CustomizationPolicy extends BasePolicy
{
    public $key = 'customization';

    public function delete(Model $user, $model): bool
    {
        return false;
    }

    public function create(Model $user): bool
    {
        return false;
    }
}
