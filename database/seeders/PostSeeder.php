<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::factory(20)->hasAttached(Tag::factory()->count(3))->create();

        Post::inRandomOrder()->published()->limit(5)->get()->each(function ($post) {
            $post->featured = true;
            $post->save();
        });
    }
}
