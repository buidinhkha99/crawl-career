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
        copy(resource_path('/files/the_atld.xlsx'), storage_path('/app/public/the_atld.xlsx'));
        copy(resource_path('/files/the_atld.xlsx'), storage_path('/app/public/the_atd.xlsx'));
        copy(resource_path('/files/giay_chung_nhan.xlsx'), storage_path('/app/public/giay_chung_nhan.xlsx'));
    }
}
