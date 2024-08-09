<?php

namespace Database\Seeders;

use Harishdurga\LaravelQuiz\Models\QuestionType;
use Illuminate\Database\Seeder;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questionTypes = [
            [
                'name' => 'Một đáp án',
                'type' => \App\Enums\QuestionType::One_Answer,
            ],
            //            [
            //                'name' => 'Câu hỏi có nhiều đáp án đúng',
            //                'type' => \App\Enums\QuestionType::Multiple_Answer
            //            ],
            //            [
            //                 'name' => 'fill_the_blank',
            //                 'type' => \App\Enums\QuestionType::Test_Answer
            //            ]
        ];
        foreach ($questionTypes as $questionType) {
            QuestionType::create($questionType);
        }
    }
}
