<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
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
                'key' => 'languages',
                'value' => '[{"value":"Vietnamese","key":"vi","default":1}]',
            ],
            [
                'key' => 'country_language',
                'value' => '[{"country":"VN","language":"vi"}]',
            ],
            [
                'key' => 'background',
                'value' => '#263238',
            ],
            [
                'key' => 'background_option',
                'value' => 'color',
            ],
            [
                'key' => 'button_color_background',
                'value' => '#1a237e',
            ],
            [
                'key' => 'button_icon_color',
                'value' => '#ffffff',
            ],
            [
                'key' => 'button_text_color',
                'value' => '#ffffff',
            ],
            [
                'key' => 'favicon',
                'value' => 'https://brocos.io/storage/media/2/logo-footer.svg',
            ],
            [
                'key' => 'favicon_option',
                'value' => 'image_url',
            ],
            [
                'key' => 'font_color',
                'value' => '#ffffff',
            ],
            [
                'key' => 'font_name',
                'value' => 'Roboto',
            ],
            [
                'key' => 'font_url',
                'value' => 'https://fonts.googleapis.com/css2?family=Roboto',
            ],

            [
                'key' => 'tables_search',
                'value' => "[{'layout':'post','key':'c1YSeFqqvoBYSZUd','attributes':{'path_details':'\/detail'}}]",
            ],
            [
                'key' => 'background_input_form',
                'value' => '#ffffff',
            ],
            [
                'key' => 'color_border_input_form',
                'value' => '#40a0a0',
            ],
            [
                'key' => 'color_placeholder_input_form',
                'value' => '#40a0a0',
            ],
            [
                'key' => 'color_text_title_form',
                'value' => '#ffffff',
            ],
            [
                'key' => 'content_default_page_error',
                'value' => file_get_contents(resource_path('views/errors/template.blade.php')),
            ],
            [
                'key' => 'rule',
                'value' => file_get_contents(resource_path('views/rule/template.blade.php')),
            ],
            [
                'key' => 'exam_result_pdf',
                'value' => file_get_contents(resource_path('views/exams.blade.php')),
            ],
            [
                'key' => 'company_name',
                'value' => false,
            ],
            [
                'key' => 'place',
                'value' => true,
            ],
            [
                'key' => 'date_time',
                'value' => true,
            ],
            [
                'key' => 'title',
                'value' => true,
            ],
            [
                'key' => 'note',
                'value' => true,
            ],
            [
                'key' => 'verifier',
                'value' => true,
            ],
            [
                'key' => 'reporter',
                'value' => true,
            ],
            [
                'key' => 'represent',
                'value' => true
            ],
            [
                'key' => 'content_page_pdf_report',
                'value' => file_get_contents(resource_path('views/report.blade.php')),
            ],
        ];

        collect($settings)->each(function ($setting) {
            Setting::create($setting);
        });
    }
}
