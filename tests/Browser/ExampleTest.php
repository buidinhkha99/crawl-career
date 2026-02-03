<?php

namespace Tests\Browser;

use App\Exports\JobExportTopCV;
use Exception;
use Illuminate\Support\Facades\Log;
use Laravel\Dusk\Browser;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\DomCrawler\Crawler;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     * @throws Exception
     */
    public function testBasicExample(): void
    {
        $i = 7;
        $dataOnePage = $this->getDataTopCV($i);
        if (empty($dataOnePage)) {
            throw new Exception ('Get data top cv error');
        }

        $path = "topCV-page-$i.xlsx";
        Excel::store((new JobExportTopCV($dataOnePage)), $path, 'public');
    }

    private function getDataTopCV(int $i)
    {
        $data = [];
        $this->browse(function (Browser $browser) use (&$data, $i) {
            $browser->assertGuest()->visit("https://www.topcv.vn/tim-viec-lam-moi-nhat?type_keyword=0&page=$i&sba=1");
            $html = $browser->script('return document.documentElement.outerHTML');
            $crawler = new Crawler($html);
            // Duyệt qua từng phần tử `job-item-search-result`
            $index = 1;
            $crawler->filterXPath('//*[@id="main"]/div[1]/div[4]/div[2]/div/div/div[3]/div[1]/div[1]/div[1]/div[1]/div')->each(function (Crawler $node) use (&$data, &$index) {
                $node->filter('.job-item-search-result')->each(function (Crawler $node) use (&$data, &$index) {
                    try {
                        // Doanh nghiệp
                        $companyName = $node->filter('.company .company-name')->text();
                    } catch (Exception $e) {
                        Log::error('[Crawl-Top-CV] Get data company failed. Index: ' . $index . ' Error: ' . $e->getMessage() . ' Response: ' . json_encode($node));
                    }

                    try {
                        // Ngành hoạt động
                        $industries = $node->filter('.tag .remaining-items')->attr('data-original-title');
                    } catch (Exception $e) {
                        try {
                            $industries = $node->filter('.tag')->text();
                        } catch (Exception $e) {
                            Log::error('[Crawl-Top-CV] Get data industries failed. Index: ' . $index . ' Error: ' . $e->getMessage() . ' Response: ' . json_encode($node));
                        }
                    }

                    try {
                        // Link truy cập doanh nghiệp
                        $companyLink = $node->filter('.company')->attr('href');
                    } catch (Exception $e) {
                        Log::error('[Crawl-Top-CV] Get data  companyLink failed. Index: ' . $index . ' Error: ' . $e->getMessage() . ' Response: ' . json_encode($node));
                    }

                    try {
                        // Khu vực
                        $location = $node->filter('.address')->attr('data-original-title');
                    } catch (Exception $e) {
                        Log::error('[Crawl-Top-CV] Get data location failed. Index: ' . $index . ' Error: ' . $e->getMessage() . ' Response: ' . json_encode($node));
                    }

                    try {
                        // Job title
                        $jobTitle = $node->filter('.title a span')->attr('data-original-title');
                    } catch (Exception $e) {
                        Log::error('[Crawl-Top-CV] Get data jobTitle failed. Index: ' . $index . ' Error: ' . $e->getMessage() . ' Response: ' . json_encode($node));
                    }

                    try {
                        // Link job
                        $jobLink = $node->filter('.title a')->attr('href');
                    } catch (Exception $e) {
                        Log::error('[Crawl-Top-CV] Get data jobLink failed. Index: ' . $index . ' Error: ' . $e->getMessage() . ' Response: ' . json_encode($node));
                    }

                    try {
                        // Lương
                        $salary = $node->filter('.title-salary')->text();
                    } catch (Exception $e) {
                        Log::error('[Crawl-Top-CV] Get data salary failed. Index: ' . $index . ' Error: ' . $e->getMessage() . ' Response: ' . json_encode($node));
                    }

                    $data[] = [
                        $companyName ?? null,
                        $industries ?? null,
                        $companyLink ?? null,
                        $location ?? null,
                        $jobTitle ?? null,
                        $jobLink ?? null,
                        null,
                        null,
                        $salary ?? null
                    ];

                    $index++;
                });
            });

            $browser->quit();
        });

        return $data;
    }
}
