<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    public function show(Request $request, $id)
    {
        $user = auth('api')->user();
        $lesson = Lesson::where('id', $id)->with(['questions' => fn($query) => $query->with('options')])->first();
        if (empty($lesson)) {
            return response()->json(['message' => __('Not Found!')], 404);
        }

        return [
            'id' => $lesson->id,
            'name' => $lesson->name,
            'type-show' => $lesson->document_type,
            'link-driver' => $lesson->link,
            'content' => $lesson->content,
            'documents' => $lesson->documents,
            'link_media' => $lesson->link_media,
            'next_lesson' => $this->nextLessonUrl($lesson),
            'prev_lesson' => $this->prevLessonUrl($lesson),
            'complete_theory' => $lesson->users()->where('users.id', $user?->id)->first()?->pivot->complete_theory ? 'true' : 'false',
            'questions' => $lesson->questions?->map(function ($question) use ($user, $lesson) {
                $answer_ids = $lesson->histories()->where('user_id', $user?->id)->where('question_id', $question->id)->get()->pluck('pivot.question_option_id');
                return [
                    'id' => $question->id,
                    'content' => $question->name,
                    'question_type' => $question->question_type?->type,
                    'answered' => $user ? $answer_ids?->first() : null,
                    'answers' => $question->answers?->map(function ($answer) use($answer_ids, $user){
                        return [
                            'id' => Arr::get($answer, 'id'),
                            'content' => Arr::get($answer, 'name'),
                            'is_correct' => Arr::get($answer, 'is_correct'),
                            'choosed' =>  $user ? $answer_ids->contains(Arr::get($answer, 'id')) : null,
                        ];
                    })
                ];
            }),
        ];
    }

    public function submit(Request $request, $id)
    {
        $user = auth('api')?->user();
        if (empty($user)) {
            return response()->json(['message' => __('Unauthorized !')], 401);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:theory,question'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $lesson = Lesson::where('id', $id)->with(['questions' => fn($query) => $query->with('options')])->first();
        if (empty($lesson)) {
            return response()->json(['message' => __('Not Found!')], 404);
        }

        // complete theory lesson
        if ($request->get('type') == 'theory') {
            $this->saveUserInLesson($lesson, $user, true);

            return response()->json(['message' => __('You have completed the theory of the lecture :attribute.', [
                'attribute' => $lesson->name
            ])]);
        }

        // complete question lesson
        $response = $this->completeQuestion($request, $lesson, $user);

        return response()->json(['message' => $response['message']], $response['status']);
    }

    protected function nextLessonUrl(Lesson $lesson): ?string
    {
       $topic = $lesson->topic;
       $next_lesson =  $topic->lessons()->where('lessons.id', '>', $lesson->id)->first();
       if (empty($next_lesson)) {
           $next_lesson = $topic->lessons()->where('lessons.id', '!=', $lesson->id)->first();
       }

       return !empty($next_lesson) ? "/lessons/$next_lesson->id" : null;
    }

    protected function prevLessonUrl(Lesson $lesson): ?string
    {
        $topic = $lesson->topic;
        $prev_lesson =  $topic->lessons()->where('lessons.id', '<', $lesson->id)->orderBy('id', 'desc')->first();
        if (empty($prev_lesson)) {
            $prev_lesson = $topic->lessons()->where('lessons.id', '!=', $lesson->id)->orderBy('id', 'desc')->first();
        }

        return !empty($prev_lesson) ? "/lessons/$prev_lesson->id" : null;
    }

    protected function completeQuestion(Request $request, Lesson $lesson, $user) {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|integer',
            'answer_chose_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 400,
                'message' => $validator->messages()
            ];
        }

        $question_id = $request->get('question_id');
        $question = Question::where('id', $question_id)->first();
        if (empty($question)) {
            return [
                'status' => 404,
                'message' => __('Not Found!')
            ];
        }

        // check question exists in lesson
        if (!$lesson->questions->pluck('id')->contains($question_id)) {
            return [
                'status' => 400,
                'message' => __('The answer does not exist in the question')
            ];
        }

        // check answer exists in question
        $answer_id = $request->get('answer_chose_id');
        if (!$question->answers->pluck('id')->contains($answer_id)) {
            return [
                'status' => 400,
                'message' => __('The answer does not exist in the question')
            ];
        }

        // save user answer question in lesson
        $history_exists = $lesson->histories()->where('user_id', $user->id)->where('question_id', $question_id)->where('question_option_id',  $answer_id)->exists();
        if (!$history_exists) {
            // delete old answer
            $lesson->histories()->wherePivot('question_id', $question_id)->detach($user->id);

            // create a new history
            $lesson->histories()->attach($user->id, [
                'question_id' => $question_id,
                'question_option_id' => $answer_id,
                'is_correct' => $question->answers->where('is_correct', true)->pluck('id')->contains($answer_id)
            ]);
        }

        $complete_theory = (bool)$lesson->users()->where('users.id', $user?->id)->first()?->pivot->complete_theory;
        $this->saveUserInLesson($lesson, $user, $complete_theory);

        return [
            'status' => 200,
            'message' => __('The system has recorded the answer')
        ];
    }

    protected function saveUserInLesson(Lesson $lesson, $user, $complete_theory): void
    {
        // update/create progress user in lesson
        $question_ids = $lesson->questions->pluck('id');
        $history_question_ids =$lesson->histories()->where('user_id', $user->id)->where('is_correct', true)->get()->pluck('pivot.question_id');
        $is_complete_questions = $question_ids->diff($history_question_ids)->isEmpty();

        $is_exists = $lesson->users()->where('user_id', $user->id)->exists();
        if (!$is_exists) {
            $lesson->users()->attach($user->id, [
                'complete_theory' => $complete_theory,
                'is_complete' => $complete_theory && $is_complete_questions,
            ]);
        }

        $lesson->users()->updateExistingPivot($user, [
            'complete_theory' => $complete_theory,
            'is_complete' => $complete_theory && $is_complete_questions,
            'created_at' => now()
        ], false);
    }

}
