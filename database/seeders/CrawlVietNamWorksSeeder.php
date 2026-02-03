<?php

namespace Database\Seeders;

use App\Exports\JobExport;
use App\Exports\JobExportCareerviet;
use App\Imports\UserImportCareerViet;
use App\Imports\ImportVietNameWork;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class CrawlVietNamWorksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = 'vietnamworks/vietnamworks.xlsx';

        Excel::store((new JobExport()), $path, 'public');
        Excel::import(new ImportVietNameWork(), storage_path('app/public/vietnamworks/vietnamworks.xlsx'));
    }
}
