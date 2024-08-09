<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exam = Exam::create([
            'name' => 'Exam 1',
            'question_amount' => 20,
            'rule' => file_get_contents(resource_path('views/rule/template.blade.php')),
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now()->addDays(5),
        ]);

        for ($i = 0; $i < 3; $i++) {
            $quiz = $exam->quizzes()->create([
                'name' => 'Quiz '.($i + 1),
                'duration' => 60,
            ]);

            $quiz->questions()->attach(Question::all()->random(20));
        }
    }
}
