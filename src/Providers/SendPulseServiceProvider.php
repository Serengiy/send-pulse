<?php

namespace Serengiy\SendPulse\Providers;

use Illuminate\Support\ServiceProvider;

class SendPulseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/send-pulse.php' => config_path('send-pulse.php'),
        ]);
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }
}
