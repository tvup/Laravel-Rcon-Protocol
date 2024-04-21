<?php

namespace RMinks\RCON\Services;

use RMinks\RCON\Contracts\Services\ResponseServiceContract;

class ResponseService implements ResponseServiceContract
{
    private array $responses = [];


    public function addResponse(string $response): bool
    {
        return array_push($this->responses, $response) ? true : false;
    }

    public function getAllResponses(): array
    {
        return $this->responses;
    }

    public function getLastResponse(): string
    {
        return $this->responses[count($this->responses) - 1];
    }

    public function getResponse(int $id): string
    {
        return $this->responses[$id];
    }
}
