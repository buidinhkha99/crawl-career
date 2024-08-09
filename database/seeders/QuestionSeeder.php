<?php

namespace Database\Seeders;

use App\Enums\QuestionType;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $questions = Question::factory(20)->create();

            $topic = Topic::firstOrCreate([
                'name' => 'Topic '.($i + 1),
            ]);

            $topic->questions()->attach($questions->pluck('id'));

            $questions->each(function ($question) {
                for ($j = 0; $j < 4; $j++) {
                    if ($j === 0) {
                        $question->options()->create([
                            'name' => fake()->words(2, true),
                            'is_correct' => true,
                        ]);

                        continue;
                    }

                    if ($j === 1 && $question->getAttribute('question_type')->getAttribute('type') === QuestionType::Multiple_Answer) {
                        $question->options()->create([
                            'name' => fake()->words(2, true),
                            'is_correct' => true,
                        ]);

                        continue;
                    }

                    $question->options()->create([
                        'name' => fake()->words(2, true),
                        'is_correct' => false,
                    ]);
                }
            });
        }
    }
}
