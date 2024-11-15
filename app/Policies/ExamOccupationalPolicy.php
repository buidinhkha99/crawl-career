<?php

namespace App\Policies;

use App\Enums\ExamStatus;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ExamOccupationalPolicy extends ExamPolicy
{
    public $key = 'ExamOccupational';
}
