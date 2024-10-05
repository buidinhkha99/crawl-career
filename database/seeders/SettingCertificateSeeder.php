<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingCertificateSeeder extends Seeder
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
                'key' => 'pdf_occupational_certificate',
                'value' => file_get_contents(resource_path('views/certificate-occupational.blade.php')),
            ],
            [
                'key' => 'pdf_electrical_certificate',
                'value' => file_get_contents(resource_path('views/certificate-electrical.blade.php')),
            ],
            [
                'key' => 'pdf_paper_certificate',
                'value' => file_get_contents(resource_path('views/certificate-paper.blade.php')),
            ],
        ];

        collect($settings)->each(function ($setting) {
            Setting::updateOrCreate([
                'key' => $setting['key'],
            ], $setting);
        });
    }
}
