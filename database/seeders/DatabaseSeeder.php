<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@brocos.io',
            'username' => 'admin'
        ]);

        $this->call([
            RolesAndPermissionsSeeder::class,
            CustomizationSeeder::class,
            SettingSeeder::class,
            QuestionTypeSeeder::class,
            FileTemplateSeeder::class,
            PageLmsSeeder::class,
        ]);
    }
}
