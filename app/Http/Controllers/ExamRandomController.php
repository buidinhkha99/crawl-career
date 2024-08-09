<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Topic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ExamRandomController extends Controller
{
    public function topics(Request $request)
    {
        try {
            return [
                "duration" => (int)Setting::get('duration') * 60,
                "score_pass" => (int)Setting::get('score_pass_quiz'),
                "total_questions" => (int)Setting::get('question_amount_quiz'),
                "questions" => !empty(Setting::get('kit')) ? Setting::get('kit')->map(function ($topic) {
                    $topics = Topic::where('name', $topic['topics'])->first();
                    if( $topics->questions()->get()->count() < $topic['amount']){
                        throw new Exception(__("The number of questions in the question group is not enough for random suggestions!"));
                    }
                    return $topics->questions()->inRandomOrder()->limit($topic['amount'])->get()->map(function ($question) {
                        return [
                            'id' => $question->id,
                            'question_type' => $question->question_type?->type,
                            'content' => $question->name,
                            'answers' => $question->answers->map(function ($anwser) {

                                return [
                                    'id' => Arr::get($anwser, 'id'),
                                    'content' => Arr::get($anwser, 'name'),
                                    'is_correct' => Arr::get($anwser, 'is_correct'),
                                ];
                            })
                        ];
                    });
                })->collapse() : []
            ];
        } catch (\Exception $e) {
            return  response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function answerRandom(Request $request)
    {
        $topics = Setting::get('kit')->map(function ($topic) {

            return Topic::where('name', $topic['topics'])->first()->questions()->get()->map(function ($question) {
                return [
                    'name' => $question->name,
                    'id' => $question->id,
                    'answers' => $question->answers
                ];
            });
        })->collapse();
        $questions = collect($request->all())->map(function ($question) use ($topics) {
            $id_correct = $topics->where('id', $question['id'])->first()['answers']->where('is_correct', 1)->first()['id'];
            $answer_question = collect($question['answers'])->map(fn ($answer) => collect($answer));
            $is_choosed = $answer_question->where('choosed', true)->count() > 0;

            if ($is_choosed && collect($question['answers'])->where(fn ($answer) => $answer['id'] === $id_correct && $answer['choosed'] === true)->count() > 0) {
                return [
                    'id_question' => $question['id'],
                    'status' => true,
                    'id_correct' => $id_correct
                ];
            };
            if ($is_choosed && collect($question['answers'])->where(fn ($answer) => $answer['id'] == $id_correct && $answer['choosed'] == false)->count() > 0) {
                return [
                    'id_question' => $question['id'],
                    'status' => false,
                    'id_correct' => $id_correct
                ];
            };
            if (!$is_choosed) {
                return [
                    'id_question' => $question['id'],
                    'status' => null,
                    'id_correct' => $id_correct
                ];
            };
        });
        return $questions;
    }
}
