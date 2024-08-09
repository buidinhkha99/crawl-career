<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;

class TagPolicy extends BasePolicy
{
    public $key = 'tag';

    public function attachAnyPost(Model $user, $model): bool
    {
        return $this->hasPermissionTo($user, 'update');
    }

    public function attachPost(Model $user, $model, $post): bool
    {
        return $this->hasPermissionTo($user, 'update');
    }

    public function detachPost(Model $user, $model, $post): bool
    {
        return $this->hasPermissionTo($user, 'update');
    }
}
