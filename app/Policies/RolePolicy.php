<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class RolePolicy extends BasePolicy
{
    /**
     * @var string
     */
    protected $key = 'role';

    public function view(Model $user, $model): bool
    {
        if ($user->isSuperAdmin())
            return true;

        if (!parent::view($user, $model)) {
            return false;
        }

        return Permission::where('name', $model->getAttribute('name'))->exists() && $user->hasPermissionTo($model->getAttribute('name'));
    }

    public function update(Model $user, $model): bool
    {
        if ($user->isSuperAdmin())
            return true;

        if (!parent::update($user, $model)) {
            return false;
        }

        return Permission::where('name', $model->getAttribute('name'))->exists() && $user->hasPermissionTo($model->getAttribute('name'));
    }

    public function delete(Model $user, $model): bool
    {
        if ($user->isSuperAdmin())
            return true;

        if (!parent::delete($user, $model)) {
            return false;
        }

        return Permission::where('name', $model->getAttribute('name'))->exists() && $user->hasPermissionTo($model->getAttribute('name'));
    }

    public function attachAnyUser(User $user, Role $role): bool
    {
        if (!$this->view($user, $role)) return false;

        return $user->hasPermissionTo('attachUserInRole');
    }

    public function attachUser(User $user, Role $role, User $model): bool
    {
        if (!$this->view($user, $role)) return false;

        return $user->isSuperAdmin() || $user->hasPermissionTo('attachUserInRole');
    }

    public function detachUser(User $user, Role $role, User $model): bool
    {
        if (!$this->view($user, $role)) return false;

        return $user->isSuperAdmin() || $user->hasPermissionTo('detachUserInRole');
    }
}
