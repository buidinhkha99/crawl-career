<?php

namespace Database\Seeders;

use App\Console\Commands\FulfilledExam;
use App\Enums\ExaminationStatus;
use App\Models\Exam;
use App\Models\Examination;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExaminationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($k = 0; $k <= 22; $k++) {
            $start_at = fake()->dateTimeBetween('-4 months');
            $exam = Exam::create([
                'name' => "Exam $k",
                'question_amount' => 20,
                'rule' => file_get_contents(resource_path('views/rule/template.blade.php')),
                'start_at' => $start_at,
                'end_at' => (new Carbon($start_at))->addDays(5),
            ]);

            for ($i = 0; $i < 3; $i++) {
                $quiz = $exam->quizzes()->create([
                    'name' => 'Quiz '.($i + 1),
                    'duration' => 60,
                ]);

                $quiz->questions()->attach(Question::all()->random(20));
                $quiz->users()->attach(User::inRandomOrder()->limit(10)->get());
            }
        }

       Exam::inRandomOrder()->get()?->each(function ($exam) {
           $exam->quizzes->each(function ($quiz) use ($exam) {
               $quiz->users->take(7)->each(function ($user) use ($exam, $quiz) {
                   $correct_answer =  fake()->numberBetween(0, 20);
                   $wrong_answer =  fake()->numberBetween(0,20 - $correct_answer);
                   $unanswered =  fake()->numberBetween(0, 20 - $correct_answer - $wrong_answer);
                   $score = fake()->numberBetween(1, 10);
                   Examination::create([
                       'exam_id' => $exam->id,
                       'quiz_id' => $quiz->id,
                       'user_id' => $user->id,
                       'start_time' => $exam->start_at,
                       'end_time' => $exam->end_at->addMinute(fake()->numberBetween(10, 60)),
                       'duration' => $exam->end_at->addMinute(fake()->numberBetween(10, 60))->diffInSeconds($exam->start_at),
                       'correct_answer' => $correct_answer,
                       'wrong_answer' => $wrong_answer,
                       'unanswered' => $unanswered,
                       'score' => $score,
                       'state' => $score >= $exam->score_pass ? ExaminationStatus::Pass : ExaminationStatus::Fail,
                       'examination' => null,
                       'dob' => $user->dob,
                       'username' => $user->username,
                       'gender' => $user->gender,
                       'name' => $user->name,
                       'position' => $user->position,
                       'department' => $user->department,
                       'factory_name' => $user->factory_name,
                       'exam_name' => $exam->name,
                       'start_time_exam' => $exam->start_at,
                       'end_time_exam' => $exam->end_at,
                       'uuid' => Str::uuid()->toString(),
                       'group' => $user->group?->name,
                       'quiz_name' => $quiz->name,
                       'avatar_url' => $user->avatar_url,
                       'avatar' => $user->avatar,
                       'employee_code' => $user->employee_code,
                       'created_at' => $exam->start_at,
                   ]);
               });
           });
       });
        (new FulfilledExam())->handle();
        $exams = Exam::where('end_at', '<=', now())->where('fulfilled', false)->get();
        $exams->each(function (Exam $exam) {
            $exam->getAttribute('quizzes')->each(function (Quiz $quiz) use ($exam) {
                $quiz->getAttribute('users')->each(function (User $user) use ($quiz, $exam) {
                    if ($quiz->examinations()->where('user_id', $user->getAttribute('id'))->exists()) {
                        return;
                    }

                    $quiz->examinations()->create([
                        'exam_id' => $exam->getAttribute('id'),
                        'user_id' => $user->getAttribute('id'),
                        'unanswered' => $quiz->questions()->count(),
                        'dob' => $user->getAttribute('dob'),
                        'username' => $user->getAttribute('username'),
                        'gender' => $user->getAttribute('gender'),
                        'name' => $user->getAttribute('name'),
                        'position' => $user->getAttribute('position'),
                        'department' => $user->getAttribute('department'),
                        'factory_name' => $user->getAttribute('factory_name'),
                        'exam_name' => $exam->getAttribute('name'),
                        'start_time_exam' => $exam->getAttribute('start_at'),
                        'end_time_exam' => $exam->getAttribute('end_at'),
                        'uuid' => Str::uuid()->toString(),
                        'group' => $user->getAttribute('group')?->getAttribute('name'),
                        'quiz_name' => $quiz->getAttribute('name'),
                        'avatar_url' => $user->getAttribute('avatar_url'),
                        'avatar' => $user->getAttribute('avatar'),
                        'employee_code' => $user->getAttribute('employee_code'),
                        'created_at' => $exam->getAttribute('end_at'),
                    ]);
                });
            });

            $exam->setAttribute('fulfilled', true);
            $exam->save();
        });
    }
}
