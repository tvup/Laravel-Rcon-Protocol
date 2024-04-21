<?php

namespace RMinks\RCON;

use RMinks\RCON\Contracts\Services\RCONServiceContract;
use RMinks\RCON\Contracts\Services\ResponseServiceContract;
use RMinks\RCON\Services\RCONService;
use RMinks\RCON\Services\ResponseService;
use Illuminate\Support\Facades\App;

class RCON
{
    protected RCONService $RCONService;

    public ResponseService $ResponseService;


    public function __construct(string $host, int $port, string $password, float $timeout)
    {
        $this->RCONService = App::make(
            RCONServiceContract::class,
            [
                'host' => $host,
                'port' => $port,
                'password' => $password,
                'timeout' => $timeout
            ]
        );

        $this->ResponseService = App::make(ResponseServiceContract::class);
    }

    public function sendCommand($command)
    {
        $this->RCONService->sendCommand($command);
    }
}
