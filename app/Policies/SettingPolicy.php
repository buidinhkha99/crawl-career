<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SettingPolicy
{
    public function viewAny(Model $user, string $type): bool
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo(__FUNCTION__.$type);
    }

    public function view(Model $user, $model): bool
    {
        $key = Str::studly(request()->get('path'));
        if (!$key) {
            return true;
        }

        return $user->isSuperAdmin() || $user->hasPermissionTo(__FUNCTION__.$key);
    }

    public function update(Model $user, $model): bool
    {
        $key = Str::studly(request()->get('path'));
        if (!$key || !self::view($user, $model)) {
            return false;
        }

        return $user->isSuperAdmin() || $user->hasPermissionTo(__FUNCTION__.$key);
    }
}
