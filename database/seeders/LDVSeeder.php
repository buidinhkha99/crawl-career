<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LDVSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            QuestionSeeder::class,
            MockQuizSeeder::class,
            LessonSeeder::class,
        ]);
    }
}
