<?php

namespace Database\Seeders;

use App\Exports\JobExport;
use App\Exports\JobExportCareerviet;
use App\Imports\UserImportCareerViet;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class CrawlCareerVietSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = 'careerviet.xlsx';
        Excel::store((new JobExportCareerviet()), $path, 'public');
    }
}
