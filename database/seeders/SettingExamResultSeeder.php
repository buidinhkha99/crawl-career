<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingExamResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'key' => 'exam_result_pdf',
                'value' => file_get_contents(resource_path('views/exams.blade.php')),
            ],
        ];

        collect($settings)->each(function ($setting) {
            Setting::where('key', $setting['key'])->update($setting);
        });
    }
}
