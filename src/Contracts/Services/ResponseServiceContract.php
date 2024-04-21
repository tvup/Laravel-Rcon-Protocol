<?php

namespace RMinks\RCON\Contracts\Services;

interface ResponseServiceContract
{
    public function addResponse(string $response): bool;

    public function getResponse(int $id): string;

    public function getLastResponse(): string;

    public function getAllResponses(): array;
}
