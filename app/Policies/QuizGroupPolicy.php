<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;
use Sereny\NovaPermissions\Policies\BasePolicy;

class QuizGroupPolicy extends BasePolicy
{
    protected $key = 'quizGroup';
}
