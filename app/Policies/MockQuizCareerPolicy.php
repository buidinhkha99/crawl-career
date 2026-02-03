<?php

namespace App\Policies;

use App\Enums\ExamStatus;
use App\Models\MockQuiz;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sereny\NovaPermissions\Policies\BasePolicy;

class MockQuizCareerPolicy extends MockQuizPolicy
{
    protected $key = 'MockQuizCareer';
}
