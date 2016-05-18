<?php

namespace Riverskies\Laravel\MobileDetect;

use Detection\MobileDetect;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class MobileDetectServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerBladeDirectives();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('mobile-detect', function ($app) {
            return new MobileDetect;
        });
    }

    private function registerBladeDirectives()
    {
        $this->registerDesktopDirectives();
        $this->registerHandheldDirectives();
        $this->registerTabletDirectives();
        $this->registerMobileDirectives();
    }

    private function registerDesktopDirectives()
    {
        Blade::directive('desktop', function ($expression) {
            return "
                <?php if (!app('mobile-detect')->isMobile()) : ?>
            ";
        });

        Blade::directive('elsedesktop', function ($expression) {
            return "
                <?php else: ?>
            ";
        });

        Blade::directive('enddesktop', function ($expression) {
            return "
                <?php endif; ?>
            ";
        });
    }

    private function registerHandheldDirectives()
    {
        Blade::directive('handheld', function ($expression) {
            return "
                <?php if (app('mobile-detect')->isMobile()) : ?>
            ";
        });

        Blade::directive('elsehandheld', function ($expression) {
            return "
                <?php else: ?>
            ";
        });

        Blade::directive('endhandheld', function ($expression) {
            return "
                <?php endif; ?>
            ";
        });
    }

    private function registerTabletDirectives()
    {
        Blade::directive('tablet', function ($expression) {
            return "
                <?php if (app('mobile-detect')->isTablet()) : ?>
            ";
        });

        Blade::directive('elsetablet', function ($expression) {
            return "
                <?php else: ?>
            ";
        });

        Blade::directive('endtablet', function ($expression) {
            return "
                <?php endif; ?>
            ";
        });
    }

    private function registerMobileDirectives()
    {
        Blade::directive('mobile', function ($expression) {
            return "
                <?php if (app('mobile-detect')->isMobile() && ! app('mobile-detect')->isTablet()) : ?>
            ";
        });

        Blade::directive('elsemobile', function ($expression) {
            return "
                <?php else: ?>
            ";
        });

        Blade::directive('endmobile', function ($expression) {
            return "
                <?php endif; ?>
            ";
        });
    }
}
