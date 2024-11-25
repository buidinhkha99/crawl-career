<?php

namespace App\Imports;

use App\Exports\JobExportDataConvertCareerViet;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class UserImportCareerViet extends DefaultValueBinder implements ToCollection, WithCustomValueBinder
{
    public function collection(Collection $collection): void
    {
        $dataCustoms = [];
        $rows = $collection->filter(fn($value) => $value->filter()->isNotEmpty());
        foreach ($rows as $key => $row) {
            if ($key == 0 || $key == 1) {
                $dataCustoms[] = $row;
                continue;
            }

            do {
                try {
                    $row[1] = $this->getJobDescription($row[5]);
                    break;
                } catch (Throwable $e) {
                    Log::error("Lỗi lấy job title tại hàng $key: " . $e->getMessage());
                    sleep(1);
                }
            } while (0);

            $dataCustoms[] = $row;
            Log::info("Get data job title successfully. Row: $key");

            if ($key % 1000 == 0) {
                $path = "careerviet-custom-$key.xlsx";
                Excel::store((new JobExportDataConvertCareerViet(collect($dataCustoms))), $path, 'public');
                Log::info("Save file successfully. Row: $key");
            }
        }

        $path = 'careerviet-custom.xlsx';
        Excel::store((new JobExportDataConvertCareerViet(collect($dataCustoms))), $path, 'public');
    }

    public function getJobDescription($url): mixed
    {
        // Gửi yêu cầu GET và nhận phản hồi
        $response = Http::get($url);

        // Kiểm tra trạng thái phản hồi
        if ($response->successful()) {
            // Lấy nội dung HTML từ phản hồi
            $html = $response->body();

            // Tạo một đối tượng DOMDocument
            $dom = new DOMDocument();

            // Xử lý lỗi khi tải HTML
            libxml_use_internal_errors(true);
            $dom->loadHTML($html);
            libxml_clear_errors();

            // Tạo một đối tượng DOMXPath
            $xpath = new DOMXPath($dom);

            // Sử dụng XPath để lấy giá trị
            $element = $xpath->query('//*[@id="tab-1"]/section/div[1]/div/div[2]/div/ul/li[2]/p');

            // Kiểm tra và lấy giá trị
            if ($element->length > 0) {
                $input = $element->item(0)->nodeValue;
                $cleaned = preg_replace('/[\r\n\t]+/', ' ', $input); // Thay thế \r, \n, \t bằng dấu cách
                $cleaned = trim($cleaned); // Xóa khoảng trắng ở đầu và cuối
                $parts = explode(',', $cleaned);
                $filteredParts = array_filter(array_map('trim', $parts));
                return implode(' | ', $filteredParts);

            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
