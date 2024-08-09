<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;

class BasePolicy extends \Sereny\NovaPermissions\Policies\BasePolicy
{
    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function forceDelete(Model $user, $model): bool
    {
        return $this->hasPermissionTo($user, 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function restore(Model $user, $model): bool
    {
        return $this->hasPermissionTo($user, 'create');
    }
}
