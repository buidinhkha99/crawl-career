<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionUser;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;


class TopicController extends Controller
{
    public function show(Request $request)
    {
        $user = auth('api')->user();
        $topics = Topic::whereHas('questions')->orderBy('id', 'ASC')->paginate($request->get('per_page', 10));
        if (empty($topics)) {
            return response()->json(['message' => __('No Data!')], 204);
        }

        return [
            'current_page' => $topics->currentPage(),
            'per_page' => $topics->perPage(),
            'last_page' => $topics->lastPage(),
            'total' => $topics->total(),
            'next_page_url' => $topics->nextPageUrl(),
            'prev_page_url' => $topics->previousPageUrl(),
            'data' => $topics->getCollection()->transform(function ($topic) use ($user) {
                return [
                    'id' => $topic->id,
                    'name' => $topic->name,
                    'total_question' => $topic->questions()->count(),
                    'process' => $user ? $user->questions()->wherePivot('topic_id', $topic->id)->wherePivot('is_correct', true)->count() : null,
                ];
            })
        ];
    }

    public function getTopicAndLesson(Request $request)
    {
        $user = auth('api')->user();
        $topics = Topic::whereHas('lessons')->orderBy('id', 'ASC')->paginate($request->get('per_page', 10));
        if (empty($topics)) {
            return response()->json(['message' => __('No Data!')], 204);
        }

        return [
            'current_page' => $topics->currentPage(),
            'per_page' => $topics->perPage(),
            'last_page' => $topics->lastPage(),
            'total' => $topics->total(),
            'next_page_url' => $topics->nextPageUrl(),
            'prev_page_url' => $topics->previousPageUrl(),
            'data' => $topics->map(function ($topic) use($user){
                return [
                    'id' => $topic->id,
                    'name' => $topic->getAttribute('name'),
                    'total_lesson' => $topic->lessons()->count(),
                    'total_lesson_complete' => $user?->lessons()->whereHas('topics', function ($query) use($topic) {
                        return $query->where('topic_id', $topic->id);
                    })->where('is_complete', true)->count(),
                ];
            })
        ];
    }

    public function detail(Request $request, $id)
    {
        $user = auth('api')->user();
        $topic = Topic::where('id', $id)->first();
        if (empty($topic)) {
            return response()->json(['message' => __('Not Found!')], 404);
        }

        $questions = $topic->questions()->orderBy('questions.id', 'ASC')->paginate($request->get('per_page', 10));
        return [
            'current_page' => $questions->currentPage(),
            'per_page' => $questions->perPage(),
            'last_page' => $questions->lastPage(),
            'total' => $questions->total(),
            'next_page_url' => $questions->nextPageUrl(),
            'prev_page_url' => $questions->previousPageUrl(),
            'data' => $questions->map(function ($question) use ($user) {
                $answer_ids = $user?->questions()->wherePivot('question_id', $question->id)->get()->pluck('pivot.question_option_id');
                return [
                    'id' => $question->id,
                    'content' => $question->name,
                    'answered' => $user ? $answer_ids?->first() : null,
                    'answers' => $question->answers->map(function ($answer) use ($user, $answer_ids) {
                        return [
                            'id' => Arr::get($answer, 'id'),
                            'content' => Arr::get($answer, 'name'),
                            'is_correct' => Arr::get($answer, 'is_correct'),
                            'choosed' =>  $user ? $answer_ids->contains(Arr::get($answer, 'id')) : null,

                        ];
                    })
                ];
            })
        ];
    }

    public function lessons(Request $request, $id)
    {
        $user = auth('api')->user();
        $lessons =Topic::find($id)
            ?->lessons()
            ->select('lessons.id', 'lessons.name')
            ->orderBy('lessons.id', 'ASC')
            ->paginate($request->get('per_page', 10));
        return [
            'current_page' => $lessons->currentPage(),
            'per_page' => $lessons->perPage(),
            'last_page' => $lessons->lastPage(),
            'total' => $lessons->total(),
            'next_page_url' => $lessons->nextPageUrl(),
            'prev_page_url' => $lessons->previousPageUrl(),
            'data' => $lessons->map(fn($lesson) => [
                'name' => $lesson->name,
                'is_complete' => (bool)$user?->lessons()->where('lessons.id', $lesson->id)->first()?->pivot->is_complete,
                'id' => $lesson->id
            ]) ?: []
        ];
    }

    public function getWrongAnswer(Request $request, $id) {
        $user = auth('api')->user();
        if (empty($user)) {
            return response()->json(['message' => __('Unauthorized !')], 401);
        }

        $topic = Topic::where('id', $id)->first();
        if (empty($topic)) {
            return response()->json(['message' => __('Not Found!')], 404);
        }

        $questions = QuestionUser::with('question')->where('topic_id', $topic->id)
                ->where('user_id', $user->id)
                ->where('is_correct', false)
                ->orderBy('id', 'ASC')
                ->paginate($request->get('per_page', 10));

        return [
            'current_page' => $questions->currentPage(),
            'per_page' => $questions->perPage(),
            'last_page' => $questions->lastPage(),
            'total' => $questions->total(),
            'next_page_url' => $questions->nextPageUrl(),
            'prev_page_url' => $questions->previousPageUrl(),
            'data' => $questions->pluck('question')->map(function ($question) use ($user) {
                $answer_ids = $user?->questions()->wherePivot('question_id', $question->id)->get()->pluck('pivot.question_option_id');
                return [
                    'id' => $question->id,
                    'content' => $question->name,
                    'answers' => $question->answers->map(function ($answer) use ($user, $answer_ids) {
                        return [
                            'id' => Arr::get($answer, 'id'),
                            'content' => Arr::get($answer, 'name'),
                            'is_correct' => Arr::get($answer, 'is_correct'),
                            'choosed' =>  $user ? $answer_ids->contains(Arr::get($answer, 'id')) : null,

                        ];
                    })
                ];
            })
        ];
    }
}
