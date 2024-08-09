<?php

namespace App\Observers;

use App\Models\LessonQuestion;

class LessonQuestionObserver
{
    public function deleted(LessonQuestion $lesson_user)
    {
        $lesson =  $lesson_user->lesson;
        if (!$lesson) return;

        $lesson->users->map(function($user) use ($lesson) {
            // update/create progress user in lesson
            $question_ids = $lesson->questions->pluck('id');
            $history_question_ids =$lesson->histories()->where('user_id', $user->id)->where('is_correct', true)->get()->pluck('pivot.question_id');
            $is_complete_questions = $question_ids->diff($history_question_ids)->isEmpty();

            $lesson->users()->updateExistingPivot($user, [
                'is_complete' => $user->pivot->complete_theory && $is_complete_questions,
            ], false);
        });
    }
    public function created(LessonQuestion $lesson_user)
    {
        $lesson_user->lesson?->users()->newPivotStatement()->update([
            'is_complete' => false,
        ]);
    }
}
