<?php

namespace RMinks\RCON\Services;

use RMinks\RCON\Contracts\Services\RCONServiceContract;
use RMinks\RCON\Contracts\Services\ResponseServiceContract;
use RMinks\RCON\Contracts\Services\SocketServiceContract;

class RCONService implements RCONServiceContract
{
    protected SocketService $socketService;
    protected ResponseService $responseService;

    private string $host;
    private int $port;
    private string $password;
    private float $timeout;
    private bool $authorized = false;

    const PACKET_AUTHORIZE = 5;
    const PACKET_COMMAND = 6;

    const SERVERDATA_AUTH = 3;
    const SERVERDATA_AUTH_RESPONSE = 2;
    const SERVERDATA_EXECCOMMAND = 2;
    const SERVERDATA_RESPONSE_VALUE = 0;


    public function __construct(ResponseServiceContract $responseService, SocketServiceContract $socketService, string $host, int $port, string $password, float $timeout)
    {
        $this->socketService = $socketService;
        $this->responseService = $responseService;

        $this->host = $host;
        $this->port = $port;
        $this->password = $password;
        $this->timeout = $timeout;

        $this->connect();
        $this->authorize();
    }

    public function sendCommand(string $command): bool
    {
        if ($this->authorized && $this->socketService->isConnected())
        {
            if ($this->socketService->send($this->writePacket(self::PACKET_COMMAND, self::SERVERDATA_EXECCOMMAND, $command)))
            {
                $response = $this->readPacket();
                if ($response['id'] == self::PACKET_COMMAND)
                {
                    if ($response['type'] == self::SERVERDATA_RESPONSE_VALUE)
                    {
                        $this->responseService->addResponse($response['body']);
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private function writePacket(int $PacketId, int $PacketType, string $PacketBody): string
    {
        $packet = pack('VV', $PacketId, $PacketType);
        $packet = $packet . $PacketBody . "\x00";
        $packet = $packet . "\x00";

        $packetSize = strlen($packet);

        $packet = pack('V', $packetSize) . $packet;

        return $packet;
    }

    public function connect()
    {
        $this->socketService->connect($this->host, $this->port, $this->timeout);
    }

    public function authorize(): bool
    {
        if (!$this->socketService->isConnected())
        {
            return false;
        }

        $this->socketService->send($this->writePacket(self::PACKET_AUTHORIZE, self::SERVERDATA_AUTH, $this->password));
        $response = $this->readPacket();
        /* Note by RMinks: 
            I changed it to 0 because SERVERDATA_AUTH_RESPONSE 
            was rejecting the conection with no sense in valve's games,
            I put the second condition for minecraft or any other game
            compatibility? Not tested yet*/
        if ($response['type'] == 0 || $response['type'] == self::SERVERDATA_AUTH_RESPONSE)
        {
            if ($response['id'] == self::PACKET_AUTHORIZE)
            {
                $this->authorized = true;
                return true;
            }
        }
        $this->socketService->disconnect();

        return false;
    }

    private function readPacket()
    {
        $size_data = $this->socketService->getPacket(4);
        $size_pack = unpack('V1size', $size_data);
        $size = $size_pack['size'];

        /* if size is > 4096, the response will be in multiple packets.
        this needs to be address. get more info about multi-packet responses
        from the RCON protocol specification at
        https://developer.valvesoftware.com/wiki/Source_RCON_Protocol
        currently, this script does not support multi-packet responses.*/

        $packet_data = $this->socketService->getPacket($size);
        $packet_pack = unpack('V1id/V1type/a*body', $packet_data);

        return $packet_pack;
    }
}
