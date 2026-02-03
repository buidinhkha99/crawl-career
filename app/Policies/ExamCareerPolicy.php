<?php

namespace App\Policies;

use App\Enums\ExamStatus;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ExamCareerPolicy extends ExamPolicy
{
    public $key = 'ExamCareer';
}
