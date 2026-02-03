<?php

namespace App\Policies;

use App\Enums\ExamStatus;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AttendancePolicy extends BasePolicy
{
    public $key = 'attendance';
}
