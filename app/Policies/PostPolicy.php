<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;

class PostPolicy extends BasePolicy
{
    public $key = 'post';

    public function attachAnyPostGroup(Model $user, $model): bool
    {
        return $this->hasPermissionTo($user, 'update');
    }

    public function attachPostGroup(Model $user, $model, $postGroup): bool
    {
        return $this->hasPermissionTo($user, 'update');
    }

    public function detachPostGroup(Model $user, $model, $postGroup): bool
    {
        return $this->hasPermissionTo($user, 'update');
    }
}
