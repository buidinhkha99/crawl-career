<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class V_3_0_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            FileTemplateSeeder::class,
            ImageCertificateSeeder::class,
            RolesAndPermissionsSeeder::class,
            SettingCertificateSeeder::class,
            SettingExamResultSeeder::class,
            SettingSeeder::class,
            UpdateIsPassQuizAttemptSeeder::class
        ]);
    }
}
