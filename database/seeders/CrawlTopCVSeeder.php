<?php

namespace Database\Seeders;

use App\Exports\JobExportTopCV;
use Exception;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome\ChromeProcess;
use Laravel\Dusk\ElementResolver;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\DomCrawler\Crawler;

class CrawlTopCVSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $data = [];
        for ($i = 1; $i <= 200; $i++) {
            $dataOnePage = $this->getDataTopCV($i);
            if (empty($dataOnePage)) {
                $path = "topCV-second/topCV-last-error-second.xlsx";
                Excel::store((new JobExportTopCV($data)), $path, 'public');
                throw new Exception ('Get data top cv error in page: ' . $i);
            }
            $data = array_merge_recursive($data, $dataOnePage);
            Log::info("Get data top cv in page $i successfully!");
            if ($i % 5 == 0) {
                $path = "topCV-second/topCV-page-second-$i.xlsx";
                Excel::store((new JobExportTopCV($data)), $path, 'public');
                if ($i != 200) sleep(120);
            }
        }

        $path = "topCV-second/topCV-second.xlsx";
        Excel::store((new JobExportTopCV($data)), $path, 'public');
        Log::info("Get data top cv fished successfully");
    }

     /**
     * Lấy dữ liệu từ TopCV
     * 
     * @param int $i Số trang cần lấy
     * @return array Dữ liệu đã lấy
     * @throws Exception Nếu không lấy được dữ liệu
     */
    protected function getDataTopCV($i): array
    {
        Log::info("Attempting to retrieve data for TopCV page $i");
        
        // Cấu hình ChromeDriver
        $options = (new ChromeOptions())->addArguments([
            '--headless',
            '--disable-gpu',
            '--window-size=1920,1080',
            '--no-sandbox',
            '--disable-dev-shm-usage',
            '--user-agent=Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36'
        ]);
        
        $capabilities = DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options);
        
        // Khởi tạo trình duyệt
        Log::info("Starting ChromeDriver session");
        $driver = RemoteWebDriver::create('http://localhost:9515', $capabilities, 60000, 60000);
        $browser = new Browser($driver, new ElementResolver($driver, ''));
        
        try {
            // Lấy dữ liệu từ trang
            Log::info("Retrieving data from page $i using browser session");
            $pageData = $this->getDataDetail($i, $browser);
            
            if (empty($pageData)) {
                throw new Exception("No data found in page $i");
            }
            
            Log::info("Successfully retrieved " . count($pageData) . " records from page $i");
            return $pageData;
        } catch (Exception $e) {
            Log::error("Error in getDataTopCV: " . $e->getMessage());
            throw $e;
        } finally {
            try {
                if (isset($browser)) {
                    $browser->quit();
                }
            } catch (Exception $e) {
                Log::error("Error quitting browser: " . $e->getMessage());
            }
        }
    }

    /**
     * Lấy chi tiết dữ liệu từ một trang
     * 
     * @param int $i Số trang
     * @param Browser $browser Trình duyệt đang mở
     * @return array Dữ liệu đã lấy
     * @throws Exception Nếu không lấy được dữ liệu
     */
    private function getDataDetail(int $i, Browser $browser): array
    {
        $data = [];
        Log::info("Visiting TopCV page $i...");
        
        // Thử truy cập trang web
        $browser->visit("https://www.topcv.vn/tim-viec-lam-moi-nhat?type_keyword=0&page=$i&sba=1");
        
        // Đợi trang load với timeout 10 giây
        try {
            $browser->waitFor('.job-item-search-result', 10);
        } catch (\Exception $e) {
            Log::warning("Timeout waiting for job items on page $i, will try to parse anyway");
        }
        
        // Đợi thêm 2 giây để đảm bảo JavaScript đã render xong
        sleep(2);
        
        $html = $browser->script('return document.documentElement.outerHTML');
        
        if (empty($html)) {
            throw new Exception("Empty HTML response for page $i");
        }
        
        Log::info("Parsing HTML content for page $i");
        $crawler = new Crawler($html);
        
        // Duyệt qua từng phần tử job-item-search-result với các selector phổ biến
        $jobItems = $crawler->filter('.job-item-search-result');
        
        if ($jobItems->count() === 0) {
            Log::warning("No job items found with selector '.job-item-search-result' on page $i");
            // Thử với selector khác
            $jobItems = $crawler->filter('.job-item');
            
            if ($jobItems->count() === 0) {
                // Lưu HTML preview để debug
                Log::error("HTML preview: " . substr($html, 0, 500));
                throw new Exception("No job items found with any selector on page $i");
            }
        }
        
        Log::info("Found " . $jobItems->count() . " job items on page $i");
        $index = 1;
        
        $jobItems->each(function (Crawler $node) use (&$data, &$index, $i) {
            $jobData = [null, null, null, null, null, null, null, null, null];
            
            // Doanh nghiệp
            try {
                $jobData[0] = $this->getTextContent($node, '.company .company-name') ?? 
                              $this->getTextContent($node, '.company-name');
            } catch (Exception $e) {
                Log::warning("Could not find company name for job $index on page $i: " . $e->getMessage());
            }

            // Ngành hoạt động
            try {
                $jobData[1] = $this->getAttributeContent($node, '.tag .remaining-items', 'data-original-title') ?? 
                              $this->getTextContent($node, '.tag');
            } catch (Exception $e) {
                Log::warning("Could not find industries for job $index on page $i: " . $e->getMessage());
            }

            // Link truy cập doanh nghiệp
            try {
                $jobData[2] = $this->getAttributeContent($node, '.company', 'href') ??
                              $this->getAttributeContent($node, '.company-name a', 'href');
            } catch (Exception $e) {
                Log::warning("Could not find company link for job $index on page $i: " . $e->getMessage());
            }

            // Khu vực
            try {
                $jobData[3] = $this->getAttributeContent($node, '.address', 'data-original-title') ??
                              $this->getTextContent($node, '.address');
            } catch (Exception $e) {
                Log::warning("Could not find location for job $index on page $i: " . $e->getMessage());
            }

            // Job title
            try {
                $jobData[4] = $this->getAttributeContent($node, '.title a span', 'data-original-title') ??
                              $this->getTextContent($node, '.title a');
            } catch (Exception $e) {
                Log::warning("Could not find job title for job $index on page $i: " . $e->getMessage());
            }

            // Link job
            try {
                $jobData[5] = $this->getAttributeContent($node, '.title a', 'href');
            } catch (Exception $e) {
                Log::warning("Could not find job link for job $index on page $i: " . $e->getMessage());
            }

            // Lương
            try {
                $jobData[8] = $this->getTextContent($node, '.title-salary') ??
                              $this->getTextContent($node, '.salary');
            } catch (Exception $e) {
                Log::warning("Could not find salary for job $index on page $i: " . $e->getMessage());
            }

            // Chỉ thêm vào data nếu có ít nhất company name và job title
            if ($jobData[0] !== null && $jobData[4] !== null) {
                $data[] = $jobData;
            } else {
                Log::warning("Skipping job $index on page $i due to missing essential data");
            }

            $index++;
        });

        if (empty($data)) {
            throw new Exception("No valid data could be extracted from page $i");
        }

        Log::info("Successfully extracted " . count($data) . " jobs from page $i");
        return $data;
    }
    
    /**
     * Helper để lấy nội dung text từ một element
     */
    private function getTextContent(Crawler $node, string $selector): ?string
    {
        $element = $node->filter($selector);
        if ($element->count() > 0) {
            return trim($element->text());
        }
        return null;
    }
    
    /**
     * Helper để lấy giá trị thuộc tính từ một element
     */
    private function getAttributeContent(Crawler $node, string $selector, string $attribute): ?string
    {
        $element = $node->filter($selector);
        if ($element->count() > 0) {
            return trim($element->attr($attribute));
        }
        return null;
    }
}
