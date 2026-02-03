<?php

namespace App\Console\Commands;

use App\Models\Exam;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command as CommandAlias;

class FulfilledExam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:FulfilledExam';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fulfilled Exam';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $exams = Exam::where('end_at', '<=', now())->where('fulfilled', false)->get();

        $exams->each(function (Exam $exam) {
            $exam->getAttribute('quizzes')->each(function (Quiz $quiz) use ($exam) {
                // create examination not yet for users
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
                    ]);
                });

                // save last questions for quiz
                $quiz->last_questions = $quiz->getAttribute('questions')->map(function ($question){
                    return [
                        'id' => $question->id,
                        'topic' => $question->topic?->name,
                        'question_type' => $question->question_type?->name,
                        'content' => $question->name,
                        'answers' => $question->answers
                    ];
                });

                $quiz->save();
            });

            $exam->setAttribute('fulfilled', true);
            $exam->save();
        });

        return CommandAlias::SUCCESS;
    }
}
