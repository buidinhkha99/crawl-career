<?php

namespace App\Policies;

use App\Enums\ExamStatus;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ClassroomPolicy extends BasePolicy
{
    public $key = 'classroom';
}
