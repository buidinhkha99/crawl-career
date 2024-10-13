<?php

namespace Database\Seeders;

use App\Models\QuizAttempt;
use Illuminate\Database\Seeder;

class UpdateIsPassQuizAttemptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $examinations = QuizAttempt::all();
        $examinations->each(function ($examination) {
            $examination->is_pass = $examination->state;
            $examination->save();
        });
    }
}
