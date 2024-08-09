<?php

namespace Database\Seeders;

use App\Enums\QuizType;
use App\Models\Lesson;
use App\Models\MockQuiz;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Database\Seeder;
use Outl1ne\NovaMediaHub\MediaHub;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $img_card = MediaHub::storeMediaFromBase64(base64_encode(file_get_contents(base_path().'/packages/bcs/salt/resources/img/imgCard.png')), 'imgCard.png', 'default', 'public', 'public');
        $img_logo = MediaHub::storeMediaFromBase64(base64_encode(file_get_contents(base_path().'/packages/bcs/salt/resources/img/bcs_log.png')), 'bcs_log.png', 'default', 'public', 'public');
        for ($i = 0; $i < 20; $i++) {
            $paragraphs = fake()->paragraphs(rand(6, 8));
            $title = fake()->realText(50);
            $post = "<h1>{$title}</h1>";
            foreach ($paragraphs as $para) {
                $post .= "<p>{$para}</p>";
            }

            $lesson = Lesson::firstOrCreate([
                'name' => "Lesson $i",
                'content' => $post,
                'document' => [$img_card->id, $img_logo->id]
            ]);

            $lesson->topics()->save(Topic::inRandomOrder()->first());
            $lesson->questions()->attach(Question::all()->random(20));
        }
    }
}
