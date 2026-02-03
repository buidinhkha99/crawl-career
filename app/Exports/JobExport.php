<?php

namespace App\Exports;

use App\Models\Classroom;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

class JobExport implements FromCollection, WithMapping
{
    public function __construct()
    {
    }

    public function collection()
    {
        $metadata = [];
        for ($i = 0; $i <= 85; $i++) {
            $result = Http::post('https://ms.vietnamworks.com/job-search/v1.0/search', [
                "userId" => 0,
                "query" => "",
                "filter" => [],
                "ranges" => [],
                "order" => [],
                "hitsPerPage" => 8463,
                "page" => $i,
                "retrieveFields" => [
                    "address",
                    "benefits",
                    "jobTitle",
                    "salaryMax",
                    "isSalaryVisible",
                    "jobLevelVI",
                    "isShowLogo",
                    "salaryMin",
                    "companyLogo",
                    "userId",
                    "jobLevel",
                    "jobLevelId",
                    "jobId",
                    "jobUrl",
                    "companyId",
                    "approvedOn",
                    "isAnonymous",
                    "alias",
                    "expiredOn",
                    "industries",
                    "industriesV3",
                    "workingLocations",
                    "services",
                    "companyName",
                    "salary",
                    "onlineOn",
                    "simpleServices",
                    "visibilityDisplay",
                    "isShowLogoInSearch",
                    "priorityOrder",
                    "skills",
                    "profilePublishedSiteMask",
                    "jobDescription",
                    "jobRequirement",
                    "prettySalary",
                    "requiredCoverLetter",
                    "languageSelectedVI",
                    "languageSelected",
                    "languageSelectedId",
                    "typeWorkingId",
                    "createdOn",
                    "isAdrLiteJob",
                    "summary"
                ]
            ]);

            $data = $result->object();
            $dataCleaned = collect($data->data)->map(function ($item) {
                $benefits = null;
                collect($item->benefits)->each(function($benefit) use (&$benefits) {
                    $benefits = $benefits . 'Lợi ích: ' . $benefit->benefitNameVI . '\n Mô tả: ' . $benefit->benefitNameVI . '\n';
                });

                return [
                    $item->companyName,
                    collect($item->industriesV3)->pluck('industryV3NameVI')->implode('|'),
                    $item->companyUrl,
                    $item->address,
                    $item->jobTitle,
                    $item->jobUrl,
                    $item->expiredOn,
                    $item->lastUpdatedOn,
                    $item->prettySalary,
                    $benefits,
                    strip_tags($item->jobDescription),
                    strip_tags($item->jobRequirement),
                ];
            });

            array_push($metadata, ...$dataCleaned);
        }

        return collect([
            ['Nền tảng: Vietnamworks'],
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
                'Lợi ích',
                'Mô tả công việc',
                'Yêu cầu công việc',
            ],
            ...$metadata
        ]);
    }

    public function map($row): array
    {
        return $row;
    }
}
