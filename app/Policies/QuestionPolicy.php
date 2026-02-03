<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;

class QuestionPolicy extends BasePolicy
{
    public $key = 'question';

    public function addQuestionOption(User $user, Question $question){
        return false;
    }
}
