<?php

namespace Database\Seeders;

use App\Enums\QuizType;
use App\Models\MockQuiz;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MockQuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 20; $i++) {
            $quiz = MockQuiz::firstOrCreate([
                'score_pass_quiz' => 10,
                'duration' => 20,
                'type' => QuizType::Review,
                'question_amount_quiz' => 20,
                'sort_order' => $i +1
            ]);

            $quiz->questions()->attach(Question::all()->random(20));
        }
    }
}
