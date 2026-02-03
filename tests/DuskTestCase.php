<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     */
    public static function prepare(): void
    {
        if (!static::runningInSail()) {
            static::startChromeDriver();
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
        ])->unless($this->hasHeadlessDisabled(), function (Collection $items) {
            return $items->merge([
                '--disable-gpu',
                '--headless',
//                '--disable-dev-shm-usage',
//                '--window-size=1920,1080',
//                '--user-data-dir=/home/kha/.config/google-chrome',
//                '--profile-directory=Profile 5',
//
//                '--user-data-dir=/home/kha/.config/google-chrome', // Đường dẫn thư mục dữ liệu Chrome
//                '--profile-directory=Default',

                '--accept=*/*',
                '--content-type=application/json',
                '--authority:ta.toprework.vn',
                '--scheme=https',
                '--accept-encoding=gzip, deflate, br, zstd',
                '--accept-language=en-US,en;q=0.9,ko;q=0.8,vi;q=0.7',
                '--cache-control=no-cache',
                '--origin=https://www.topcv.vn',
                '--priority=u=1, i',
                '--referer=https://www.topcv.vn/',
                '--sec-ch-ua="Google Chrome";v="131", "Chromium";v="131", "Not_A Brand";v="24"',
                '--sec-ch-ua-mobile=?0',
                '--sec-ch-ua-platform="Linux"',
                '--sec-fetch-dest=empty',
                '--sec-fetch-mode=cors',
                '--sec-fetch-site=cross-site',
                '--user-agent=Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
            ]);
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    /**
     * Determine whether the Dusk command has disabled headless mode.
     */
    protected function hasHeadlessDisabled(): bool
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
            isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }

    /**
     * Determine if the browser window should start maximized.
     */
    protected function shouldStartMaximized(): bool
    {
        return isset($_SERVER['DUSK_START_MAXIMIZED']) ||
            isset($_ENV['DUSK_START_MAXIMIZED']);
    }
}
