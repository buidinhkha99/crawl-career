<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FileTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        copy(resource_path('/files/file_example_question.xlsx'), storage_path('/app/public/file_example_question.xlsx'));
        copy(resource_path('/files/file_example_user.xlsx'), storage_path('/app/public/file_example_user.xlsx'));
        copy(resource_path('/images/default_avatar_user.png'), storage_path('/app/public/default_avatar_user.png'));
        copy(resource_path('/files/file_mau_the_ATLD.xlsx'), storage_path('/app/public/file_mau_the_ATLD.xlsx'));
        copy(resource_path('/files/file_mau_the_ATD.xlsx'), storage_path('/app/public/file_mau_the_ATD.xlsx'));
        copy(resource_path('/files/file_mau_giay_chung_nhan.xlsx'), storage_path('/app/public/file_mau_giay_chung_nhan.xlsx'));
        copy(resource_path('/images/default_signature.png'), storage_path('/app/public/default_signature.png'));
        copy(resource_path('/fonts/ARIAL.TTF'), storage_path('/app/public/ARIAL.TTF'));
        copy(resource_path('/fonts/times.ttf'), storage_path('/app/public/times.ttf'));
    }
}
