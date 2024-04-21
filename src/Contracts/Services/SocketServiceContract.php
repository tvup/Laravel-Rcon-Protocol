<?php

namespace RMinks\RCON\Contracts\Services;

interface SocketServiceContract
{
    public function connect(string $host, int $port, float $timeout): bool;

    public function disconnect();

    public function send($data): bool;

    public function getPacket(int $length);

    public function isConnected(): bool;
}
