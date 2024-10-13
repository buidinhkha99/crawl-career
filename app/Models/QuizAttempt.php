<?php

namespace App\Models;

class QuizAttempt extends \Harishdurga\LaravelQuiz\Models\QuizAttempt
{

    protected $with = ['quiz', 'participant'];

    protected $appends = ['examination'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id')->withoutGlobalScopes();
    }

    public function getExaminationAttribute()
    {
        return $this->answers->map(function($answer, $key) {
            $question = $answer->quiz_question->question;
            $questionOptions = $question->answers;
            return [
                'order' => $key + 1,
                'question_content' => $question->name,
                'is_correct' => (bool)$questionOptions->where('id', $answer->question_option_id)->where('is_correct', true)->first(),
                'answers' => $questionOptions->map(function ($anw) use($questionOptions, $answer) {
                    return [
                        'data' => $anw['name'],
                        'is_correct' => $anw['is_correct'],
                        'is_choose' => $answer->question_option_id == $anw['id']
                    ];
                })
            ];
        });
    }

    public function getStateAttribute(): string|null
    {
        return ($this->examination->where('is_correct', true)->count() / $this->quiz->question_amount_quiz * 10) >= $this->quiz->score_pass_quiz;
    }
}
