<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;
use Sereny\NovaPermissions\Policies\BasePolicy;

class UserGroupPolicy extends BasePolicy
{
    /**
     * @var string
     */
    protected $key = 'userGroup';

    public function delete(Model $user, $model): bool
    {
        return ($this->hasPermissionTo($user, 'delete') && $model->users_count <= 0) || $user->isSuperAdmin();
    }

    public function attachUser(Model $author, $model, $user): bool
    {
        return ($this->hasPermissionTo($author, 'update')
                && !$model->users()->where('users.id', $user?->id)->exists()
                && $user->roles?->isEmpty())
            || $author->isSuperAdmin();
    }

    public function attachAnyUser(Model $author, $model): bool
    {
        return $this->hasPermissionTo($author, 'update') || $author->isSuperAdmin();
    }

    public function detachUser(Model $author, $model, $user): bool
    {
        return $this->hasPermissionTo($author, 'update') || $author->isSuperAdmin();
    }
}
