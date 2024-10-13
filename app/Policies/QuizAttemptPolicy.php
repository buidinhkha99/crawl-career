<?php

namespace App\Policies;

use App\Enums\ExamStatus;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sereny\NovaPermissions\Policies\BasePolicy;

class QuizAttemptPolicy extends BasePolicy
{
    protected $key = 'quiz_attempt';

    public function create(Model $user): bool
    {
        return false;
    }

    public function update(Model $user, $model): bool
    {
        return false;
    }

    public function delete(Model $user, $model): bool
    {
        return false;
    }

    public function forceDelete(Model $user, $model): bool
    {
        return false;
    }

    public function restore(Model $user, $model): bool
    {
        return false;
    }

    public function replicate(Model $user, $model)
    {
        return false;
    }
}
