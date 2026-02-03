<?php

namespace App\Policies;

use App\Enums\ExamStatus;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AttendanceClassroomPolicy extends BasePolicy
{
    public $key = 'attendanceClassroom';

    public function create(Model $user)
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

//    public function update(Model $user, $model): bool
//    {
//        return false;
//    }

    public function restore(Model $user, $model): bool
    {
        return false;
    }

    public function replicate(Model $user, $model)
    {
        return false;
    }

    public function runAction(Model $user, $model, $action, $parameters)
    {
        return false;
    }

    public function runDestructiveAction(Model $user, $model, $action, $parameters)
    {
        return false;
    }
}
