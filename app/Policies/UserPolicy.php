<?php

namespace App\Policies;

use App\Enums\ExamStatus;
use App\Models\Quiz;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Sereny\NovaPermissions\Policies\BasePolicy;
use Spatie\Permission\Models\Permission;

class UserPolicy extends BasePolicy
{
    public $key = 'user';

    public function view(Model $user, $model): bool
    {
        if ($user->getAttribute('id') === $model->getAttribute('id') ||
            $user->isSuperAdmin()) {
            return true;
        }

        if (!parent::view($user, $model)) {
            return false;
        }

        if ($model->getAttribute('roles')->count() === 0) {
            return true;
        }

        return $model->getAttribute('roles')->contains(fn($role) => $user->hasPermissionTo($role->getAttribute('name')));
    }

    public function update(Model $user, $model): bool
    {
        if ($user->getAttribute('id') === $model->getAttribute('id') ||
            $user->isSuperAdmin()) {
            return true;
        }

        if (!parent::update($user, $model)) {
            return false;
        }

        if ($model->getAttribute('roles')->count() === 0) {
            return true;
        }

        return $model->getAttribute('roles')->contains(fn($role) => $user->hasPermissionTo($role->getAttribute('name')));
    }

    public function delete(Model $user, $model): bool
    {
        if ($user->getAttribute('id') === $model->getAttribute('id')) {
            return false;
        }

        if (!parent::delete($user, $model)) {
            return false;
        }

        if ($model->roles_count === 0) {
            return true;
        }

        return $model->getAttribute('roles')->contains(fn($role) => $user->hasPermissionTo($role->getAttribute('name')));
    }

    public function attachQuiz(User $user, User $model, Quiz $quiz): bool
    {
        return
                $this->hasPermissionTo($user, 'update') &&
                $quiz->exam()
                    ->where('end_at', '>', Carbon::now())
                    ->first() &&
                !$quiz->getAttribute('users')?->pluck('id')?->contains($model->getAttribute('id')) &&
                $quiz->status != ExamStatus::Finished;
    }

    public function detachQuiz(User $user, User $model, Quiz $quiz): bool
    {
        return (
                $this->hasPermissionTo($user, 'update') &&
                ! $quiz->exam()->where('end_at', '<=', Carbon::now())->exists()
            ) ||
            $user->isSuperAdmin();
    }
}
