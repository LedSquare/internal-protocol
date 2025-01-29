<?php

namespace InternalProtocol;

use Illuminate\Support\ServiceProvider;

class InternalProtocolServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . './stubs/provider.stub' => app_path('Providers/InternalApiProtocolServiceProvider.php'),
        ], 'provider');
        $this->publishes([
            __DIR__ . './config/internal.php' => config_path('internal.php'),
        ], 'config');
    }
}
