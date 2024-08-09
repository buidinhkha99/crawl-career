<?php

namespace App\Nova\Observer;

use App\Exceptions\AppException;
use App\Models\ExaminationMockQuiz;
use App\Models\Lesson;
use App\Models\LessonQuestion;
use App\Models\MockQuiz;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Setting;
use App\Models\Topic;
use Illuminate\Support\Arr;
use Laravel\Nova\Notifications\NovaNotification;

class QuestionObserver
{
    public function deleting(Question $question): void
    {
        // can't delete question in quiz running or incoming
        if (Quiz::whereHas('questions', function ($query) use ($question) {
            $query->where('questions.id', $question->id);
        })->whereHas('exam', function ($query) use ($question) {
            $query->where('exams.end_at', '>', now());
        })->exists()) {
            throw new AppException(__("Do not delete questions that are part of an ongoing or upcoming quiz."));
        }

        // check total question in topic for quiz random
        if (Setting::get('kit')) {
            $kits = Setting::get('kit');
            $kits->where('topics', $question->topic?->name)->each(function ($kit) use($question) {
                $count_question = Topic::where('name', $kit['topics'])->first()?->questions()->whereNot('questions.id', $question->id)->count();
                if ($kit['amount'] > $count_question) {
                    Auth()->user()->notify(
                        NovaNotification::make()
                            ->message(__("The number of questions in the :attribute is currently not enough for random questions", [
                                'attribute' => $kit['topics']
                            ]))
                            ->type('warning')
                    );
                }
            });
        }

        // check total question in topic for mock quiz
        $question->mock_quizzes->map(function($quiz) use($question){
            {
                $quiz->kit?->where('topics', $question->topic?->name)?->each(function ($kit) use($question, $quiz) {
                    $count_question = Topic::where('name', $kit['topics'])->first()?->questions()->whereNot('questions.id', $question->id)->count();
                    if ($kit['amount'] > $count_question) {
                        throw new AppException(__("The number of questions in the :topic content group is currently not enough for the :quiz mock quiz", [
                            'topic' => $kit['topics'],
                            'quiz' => $quiz->sort_order
                        ]));
                    }
                });
            }
        });

        // render questions again except id question deleted for mock quiz
        MockQuiz::whereHas('questions', function ($query) use ($question) {
            $query->where('questions.id', $question->id);
        })->get()->map(function ($quiz) use ($question) {
            $this->saveKit($quiz, $question->id);
        });

        // delete history do mock quiz
        $question->mock_quizzes?->map(function ($quiz){
            ExaminationMockQuiz::where('quiz_id', $quiz->id)->delete();
        });

        // update progress user in lesson
        $question->lessons->each(function ($lesson) {
            $lesson->users->each(function($user) use ($lesson) {
                $question_ids = $lesson->questions->pluck('id');
                $history_question_ids =$lesson->histories()->where('user_id', $user->id)->where('is_correct', true)->get()->pluck('pivot.question_id');
                $is_complete_questions = $question_ids->diff($history_question_ids)->isEmpty();

                $lesson->users()->updateExistingPivot($user, [
                    'is_complete' => $user->pivot->complete_theory && $is_complete_questions,
                ], false);
            });
        });

    }

    protected function saveKit(MockQuiz $quiz, mixed $question_id): void
    {
        $kits = collect($quiz->kit)->map(fn($kit) => collect($kit)->values());
        $question_ids = collect();
        $kits->each(function ($kit) use ($quiz, $question_ids, $question_id) {
            $topic = Topic::where('name', $kit[0])->first();
            $count_question = $topic?->questions()->whereNot('questions.id', $question_id)->count();

            if ($kit[1] > $count_question) {
                return;
            }

            $question_ids->push(...$topic?->questions()->whereNot('questions.id', $question_id)?->pluck('questions.id')?->random($kit[1]));

        });

        if ($question_ids->count() != $quiz->getAttribute('question_amount_quiz')) {
            return;
        }

        $quiz->questions()->detach();
        $quiz->questions()->attach($question_ids);
    }

    public function updated(Question $question)
    {
        $question->lessons?->map(function ($lesson) use($question){
            $lesson->histories()->where('question_id', $question->id)->detach();
            $lesson->users()->newPivotStatement()->update([
                'is_complete' => false,
            ]);
        });

        // delete history do mock quiz
        $question->mock_quizzes?->map(function ($quiz){
            ExaminationMockQuiz::where('quiz_id', $quiz->id)->delete();
        });

        // update history do question
        $question->users()->detach();
    }
}
