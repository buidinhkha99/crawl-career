<?php

namespace App\Nova\Observer;

use App\Exceptions\AppException;
use Exception;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Harishdurga\LaravelQuiz\Models\QuestionType;

class QuestionOptionObserver
{
    /**
     * @throws Exception
     */
    public function saving(QuestionOption $questionOption): void
    {
        if ($questionOption->getAttribute('question')->getAttribute('question_type')->getAttribute('name') ===
            QuestionType::find(1)?->getAttribute('name') && $questionOption->getAttribute('question')->correct_options()->count() >= 1 &&
            ! $questionOption->getAttribute('question')->correct_options()->contains('id', $questionOption->getAttribute('id'))
            && $questionOption->getAttribute('is_correct')
        ) {
            throw new AppException(__('This type of question cannot have more than one correct answer'));
        }
    }
}
