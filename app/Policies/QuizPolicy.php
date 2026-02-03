<?php

namespace App\Policies;

use App\Enums\ExamStatus;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sereny\NovaPermissions\Policies\BasePolicy;

class QuizPolicy extends BasePolicy
{
    protected $key = 'quiz';

    public function attachAnyQuestion(User $user, Model $quiz): bool
    {
        return $user->isSuperAdmin();
    }

    public function attachQuestion(User $user, Model $quiz, Question $question): bool
    {
        return $user->isSuperAdmin() && !$quiz->questions()->where('questions.id', $question?->id)->exists();
    }

    public function detachQuestion(User $user, Model $quiz, Question $question): bool
    {
        return $user->isSuperAdmin();
    }

    public function attachAnyUser(User $user, Model $quiz): bool
    {
        return ($this->hasPermissionTo($user, 'update') && $quiz->getAttribute('exam')?->getAttribute('end_at')->gt(now())) || $user->isSuperAdmin();
    }

    public function attachUser(User $user, Model $quiz, User $model): bool
    {
        return
            $this->hasPermissionTo($user, 'update') &&
            $quiz->exam()->where('end_at', '>', Carbon::now())->first() &&
            !$quiz->getAttribute('users')?->pluck('id')?->contains($model->getAttribute('id'))
            ||
            $user->isSuperAdmin();
    }

    public function detachUser(User $user, Model $quiz, User $model): bool
    {
        return (
                $this->hasPermissionTo($user, 'update') &&
                ! $quiz->exam()->where('end_at', '<=', Carbon::now())->exists()
            ) ||
            $user->isSuperAdmin();
    }

    public function update(Model $user, $model): bool
    {
        return ($this->hasPermissionTo($user, 'update') && ! Carbon::now()->gte($model?->exam?->getAttribute('start_at'))) || $user->isSuperAdmin();
    }

    public function delete(Model $user, $model): bool
    {
        return ($this->hasPermissionTo($user, 'delete') && ! Carbon::now()->gte($model?->exam?->getAttribute('start_at'))) || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function forceDelete(Model $user, $model): bool
    {
        return ($this->hasPermissionTo($user, 'delete') && ! Carbon::now()->gte($model?->exam?->getAttribute('start_at'))) || $user->isSuperAdmin();
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

    public function replicate(Model $user, $model)
    {
        if ($model->status == ExamStatus::Upcoming) return true;

        return false;
    }
}
