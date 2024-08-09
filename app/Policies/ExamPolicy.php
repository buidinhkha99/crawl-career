<?php

namespace App\Policies;

use App\Enums\ExamStatus;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ExamPolicy extends BasePolicy
{
    public $key = 'exam';

    public function update(Model $user, $model): bool
    {
        return ($this->hasPermissionTo($user, 'update') && ! Carbon::now()->gte($model->getAttribute('start_at'))) ||
            $user->isSuperAdmin();
    }

    public function delete(Model $user, $model): bool
    {
        return ($this->hasPermissionTo($user, 'delete') && ! Carbon::now()->gte($model->getAttribute('start_at'))) ||
            $user->isSuperAdmin();
    }

    public function addQuiz(User $user, Exam $exam): bool
    {
        return ($this->hasPermissionTo($user, 'update') && $exam->getAttribute('end_at') >= now()) || $user->isSuperAdmin();
    }

    public function replicate(Model $user, $model)
    {
        if ($model->status == ExamStatus::Upcoming) return true;

        return false;
    }
}
