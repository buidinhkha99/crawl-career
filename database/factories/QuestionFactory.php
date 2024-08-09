<?php

namespace Database\Factories;

use Harishdurga\LaravelQuiz\Models\QuestionType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends \Harishdurga\LaravelQuiz\Database\Factories\QuestionFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => '<p>'.fake()->words(10, true).'</p>',
            'question_type_id' => QuestionType::where('type', \App\Enums\QuestionType::One_Answer)->pluck('id')->first() ?: 1,
        ];
    }
}
