<?php

namespace App\Exports;

use App\Imports\UserImportCareerViet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class JobExportDataConvertCareerViet implements FromCollection, WithMapping
{
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function map($row): array
    {
        return is_array($row) ? $row : $row->toArray();
    }
}
