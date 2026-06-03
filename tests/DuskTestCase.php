<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;

abstract class DuskTestCase extends BaseTestCase
{
    /**
     * Prepare for Dusk test execution.
     */
    #[BeforeClass]
    public static function prepare(): void
    {
        // Redémarre le serve avec le bon .env (déjà swappé par `php artisan dusk`)
        // afin que le web server utilise dusk.sqlite et non database.sqlite.
        exec('pkill -f "artisan serve" 2>/dev/null; true');
        sleep(1);
        $cmd = sprintf(
            'php %s serve --host=127.0.0.1 --port=8000 > /dev/null 2>&1 &',
            dirname(__DIR__) . '/artisan'
        );
        exec($cmd);
        sleep(2);
        // ChromeDriver géré par le snap — démarré manuellement avant les tests :
        // /snap/chromium/current/usr/lib/chromium-browser/chromedriver --port=9515
    }

    /**
     * Clear cookies on the reused primary browser before each test.
     * Dusk keeps static::$browsers alive across tests (performance optimisation).
     * Without this, session cookies from the previous test bleed into the next one.
     */
    protected function createBrowsersFor(\Closure $callback): \Illuminate\Support\Collection
    {
        if (count(static::$browsers) > 0) {
            try {
                static::$browsers->first()->driver->manage()->deleteAllCookies();
            } catch (\Throwable) {
                // Browser session is broken (e.g. previous test crashed mid-navigation).
                // Discard it so parent::createBrowsersFor() opens a fresh one.
                static::$browsers = collect();
            }
        }

        return parent::createBrowsersFor($callback);
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            '--disable-search-engine-choice-screen',
            '--disable-smooth-scrolling',
        ])->unless($this->hasHeadlessDisabled(), function (Collection $items) {
            return $items->merge([
                '--disable-gpu',
                '--headless=new',
            ]);
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? env('DUSK_DRIVER_URL') ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }
}
