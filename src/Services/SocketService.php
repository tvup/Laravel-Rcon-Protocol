<?php

namespace RMinks\RCON\Services;

use RMinks\RCON\Contracts\Services\SocketServiceContract;

class SocketService implements SocketServiceContract
{
    private $socket;
    private bool $connected;


    public function connect(string $host, int $port, float $timeout): bool
    {
        $this->socket = fsockopen($host, $port, $error_code, $error_message, $timeout);

        if ($this->socket)
        {
            $this->connected = true;
            return true;
        }
        return false;
    }

    public function disconnect()
    {
        if ($this->socket)
        {
            fclose($this->socket);
        }
    }

    public function send($data): bool
    {
        return fwrite($this->socket, $data, strlen($data)) ? true : false;
    }

    public function getPacket(int $length)
    {
        return fread($this->socket, $length);
    }

    public function isConnected(): bool
    {
        return $this->connected;
    }
}
