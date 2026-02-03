<?php

namespace App\Exports;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Tests\Browser\ExampleTest;
use Throwable;

class JobExportTopCV implements FromCollection, WithMapping
{
    public function __construct(protected array $data)
    {
    }

    public function collection()
    {
        return collect([
            ['Nền tảng: Top cv'],
            [
                'Doanh nghiệp',
                'Ngành hoạt động',
                'Link truy cập doanh nghiệp DN',
                'Khu vực',
                'Job title',
                'Link job',
                'Ngày hết hạn',
                'Ngày cập nhật',
                'Lương',
            ],
            ...$this->data
        ]);
    }

    public function map($row): array
    {
        return $row;
    }
}
