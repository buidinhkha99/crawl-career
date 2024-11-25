<?php

namespace App\Exports;

use App\Imports\UserImportCareerViet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class JobExportCareerviet implements FromCollection, WithMapping
{
    public function __construct()
    {
    }

    public function collection()
    {
        $metadata = [];
        // number page have
        for ($i = 1; $i <= 525; $i++) {
            try {
                $result = Http::withHeaders([
                    'Accept' => 'application/json, text/javascript, */*; q=0.01',
                    'Accept-Language' => 'en-US,en;q=0.9,ko;q=0.8,vi;q=0.7',
                    'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                    'Cookie' => 'clientKey=670d3d66162cd; jobseeker=emb93uqvo05hpoqbsn5s5mjtr2',
                    'Origin' => 'https://careerviet.vn',
                    'Priority' => 'u=1, i',
                    'Referer' => 'https://careerviet.vn/viec-lam/tat-ca-viec-lam-trang-2-vi.html',
                    'Sec-CH-UA' => '"Google Chrome";v="125", "Chromium";v="125", "Not.A/Brand";v="24"',
                    'Sec-CH-UA-Mobile' => '?0',
                    'Sec-CH-UA-Platform' => '"Linux"',
                    'Sec-Fetch-Dest' => 'empty',
                    'Sec-Fetch-Mode' => 'cors',
                    'Sec-Fetch-Site' => 'same-origin',
                    'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
                    'X-Requested-With' => 'XMLHttpRequest',
                ])
                    ->asForm() // This tells Laravel to send the data as application/x-www-form-urlencoded
                    ->post('https://careerviet.vn/search-jobs', [
                        'dataOne' => 'a:1:{s:4:"PAGE";s:1:"' . $i . '";}',
                    ]);

                $data = $result->json();
                $dataCleaned = collect($data['data'])->map(function ($item, $key) {
                    return [
                        $item['EMP_NAME'],
                        $jobIndustry ?? null,
                        $item['URL_EMP_DEFAULT'],
                        collect($item['LOCATION_NAME_ARR'])->implode(','),
                        $item['JOB_TITLE'],
                        $item['LINK_JOB'],
                        $item['JOB_LASTDATE'],
                        $item['JOB_ACTIVEDATE'],
                        $item['JOB_SALARY_STRING'],
                    ];
                });

                array_push($metadata, ...$dataCleaned);
            } catch (Throwable $e) {
                Log::error("Lỗi tại trang $i: " . $e->getMessage());
                sleep(3);
            }
        }

        return collect([
            ['Nền tảng: Careerviet'],
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
            ...$metadata
        ]);
    }

    public function map($row): array
    {
        return $row;
    }
}
