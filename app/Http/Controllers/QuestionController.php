<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function submit(Request $request, $id)
    {
        $user = auth('api')?->user();
        if (empty($user)) {
            return response()->json(['message' => __('Unauthorized !')], 401);
        }

        $validator = Validator::make($request->all(), [
            'answer_chose_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $question = Question::where('id', $id)->first();
        if (empty($question)) {
            return response()->json(['message' => __('Not Found!')], 404);
        }

        $answer_id = $request->get('answer_chose_id');

        // check is answer exists in question
        if (!$question->answers->pluck('id')->contains($answer_id)) {
            return response()->json(['message' => __('The answer does not exist in the question')], 400);
        }
        $question_answer_exists = $user->questions()->where('question_id', $id)->where('question_option_id',  $answer_id)->exists();
        if (!$question_answer_exists) {
            // delete older history answer
            $user->questions()->detach($id);

            // create new answer
            $user->questions()->attach($id, [
                'topic_id' => $question->topic->id,
                'question_option_id' => $answer_id,
                'is_correct' => $question->answers->where('is_correct', true)->pluck('id')->contains($answer_id),
                'created_at' => now()
            ]);
        }

        return response()->json(['message' => __('The system has recorded the answer')]);
    }

    public function reset(Request $request, $id)
    {
        $user = auth('api')?->user();
        if (empty($user)) {
            return response()->json(['message' => __('Unauthorized !')], 401);
        }

        $question = Question::where('id', $id)->first();
        if (empty($question)) {
            return response()->json(['message' => __('Not Found!')], 404);
        }

        $user->questions()->detach($id);

        return response()->json(['message' => __('Delete history as a question successfully!')]);
    }
}
