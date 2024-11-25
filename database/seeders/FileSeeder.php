<?php

namespace Database\Seeders;

use App\Exports\JobExport;
use App\Exports\JobExportCareerviet;
use App\Imports\UserImportCareerViet;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = 'vietnamworks.xlsx';

//        Excel::store((new JobExport()), $path, 'public');

//        $path = 'careerviet.xlsx';
//        Excel::store((new JobExportCareerviet()), $path, 'public');
        // get job description and save again
        Excel::import(new UserImportCareerViet(), storage_path('app/public/careerviet.xlsx'));
    }
}
