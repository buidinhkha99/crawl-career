<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingTemplateSeeder extends Seeder
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
            [
                'key' => 'content_page_pdf_report',
                'value' => file_get_contents(resource_path('views/report.blade.php')),
            ],
        ];

        collect($settings)->each(function ($setting) {
            Setting::updateOrCreate([
                'key' => $setting['key'],
            ], [
                'value' => $setting['value'],
            ]);
        });
    }
}
