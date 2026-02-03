<?php

namespace App\Http\Controllers;

use App\Enums\ExaminationStatus;
use App\Models\ExaminationMockQuiz;
use App\Models\MockQuiz;
use App\Models\QuizGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;


class MockQuizController extends Controller
{
    public function groups(Request $request)
    {
        $groups = QuizGroup::orderBy('id', 'ASC')->get();

        return [
            'data' => $groups->map(fn($group) => [
                'id' => $group->id,
                'name' => $group->name,
                'count' => $group->quizzes()->count(),
            ]) ?: []
        ];
    }

    public function groupQuizzes(Request $request, $id)
    {
        $group = QuizGroup::with('quizzes')->where('id', $id)->firstOrFail();
        $user = auth('api')?->user();

        return [
            'data' => $group->quizzes->map(fn($quiz) => [
                'id' => $quiz->id,
                'group_id' => $quiz->group_id,
                'group_name' => $group->name,
                'status' => $user ? ExaminationMockQuiz::where('quiz_id', $quiz->id)
                    ->where('user_id', $user->id)
                    ->first()?->state ?? ExaminationStatus::NotYet : null,
                'index' => $quiz->sort_order,
            ]) ?: []
        ];
    }

    public function show(Request $request)
    {
        $quizzes = MockQuiz::with('group')->orderBy('id', 'ASC')->get();
        $user = auth('api')?->user();

        return [
            'data' => $quizzes->map(fn($quiz) => [
                'id' => $quiz->id,
                'group_id' => $quiz->group_id,
                'group_name' => $quiz->group?->name ?? null,
                'status' => $user ? ExaminationMockQuiz::where('quiz_id', $quiz->id)
                    ->where('user_id', $user->id)
                    ->first()?->state ?? ExaminationStatus::NotYet : null,
                'index' => $quiz->sort_order,
            ]) ?: []
        ];
    }

    public function detail(Request $request, $id)
    {
        $user = auth('api')?->user();
        $quiz = MockQuiz::with('questions')->where('id', $id)->first();
        if (empty($quiz)) {
            return response()->json(['message' => __('Not Found!')], 404);
        }
        $examination = ExaminationMockQuiz::where('quiz_id', $quiz->id)
            ->where('user_id', $user?->id)
            ->first();

        if ($examination && $user) {

            return [
                'is_done' => true,
                'start_time' => $examination->start_time,
                'end_time' => $examination->end_time,
                'score_pass' => $quiz->score_pass_quiz,
                'is_passed' => $examination->getAttribute('state') == ExaminationStatus::Pass,
                'score' => $examination->getAttribute('score'),
                'working_time' => $examination->getAttribute('duration') ?: 0,
                'total_questions' => $quiz->questions?->count() ?? 0,
                'right_answers' => $examination->getAttribute('correct_answer'),
                'wrong_answers' => $examination->getAttribute('wrong_answer'),
                'unanswered' => $examination->getAttribute('unanswered'),
                'questions' => $examination->getAttribute('examination'),
            ];
        }

        return [
            'is_done' => false,
            'duration' => $quiz->duration * 60,
            'score_pass' => $quiz->score_pass_quiz,
            'total_questions' => $quiz->questions?->count() ?? 0,
            'questions' => $quiz->questions?->map(function ($question) {
                return [
                    'id' => $question->id,
                    'content' => $question->name,
                    'question_type' => $question->question_type?->type,
                    'answers' => $question->answers?->map(function ($answer) {
                        return [
                            'id' => Arr::get($answer, 'id'),
                            'content' => Arr::get($answer, 'name'),
                            'is_correct' => Arr::get($answer, 'is_correct'),
                        ];
                    })
                ];
            }),
        ];
    }
}
