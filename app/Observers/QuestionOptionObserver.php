<?php

namespace App\Observers;

use App\Exceptions\AppException;
use App\Models\ExaminationMockQuiz;
use App\Models\QuestionUser;
use Exception;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Harishdurga\LaravelQuiz\Models\QuestionType;

class QuestionOptionObserver
{
    public function saved(QuestionOption $answer)
    {
        $question = $answer->question;

        $question?->lessons?->map(function ($lesson) use ($question) {
            $lesson->histories()->where('question_id', $question->id)->detach();
            $lesson->users()->newPivotStatement()->update([
                'is_complete' => false,
            ]);
        });

        // delete history do mock quiz
        $question?->mock_quizzes?->map(function ($quiz){
            ExaminationMockQuiz::where('quiz_id', $quiz->id)->delete();
        });

        // delete history do question
        $question?->users()->detach();
    }
}
