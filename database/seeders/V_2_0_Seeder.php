<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class V_2_0_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SettingTemplateSeeder::class,
            FileTemplateSeeder::class,
            V2_0_SectionSeeder::class,
        ]);
    }
}
