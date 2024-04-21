<?php

namespace RMinks\RCON\Providers;

use RMinks\RCON\Contracts\Services\RCONServiceContract;
use RMinks\RCON\Contracts\Services\ResponseServiceContract;
use RMinks\RCON\Contracts\Services\SocketServiceContract;
use RMinks\RCON\RCON;
use RMinks\RCON\Services\RCONService;
use RMinks\RCON\Services\ResponseService;
use RMinks\RCON\Services\SocketService;
use Illuminate\Support\ServiceProvider;

class RCONServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(SocketServiceContract::class, SocketService::class);

        $this->app->singleton(ResponseServiceContract::class, ResponseService::class);

        $this->app->bind(RCONServiceContract::class, RCONService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
