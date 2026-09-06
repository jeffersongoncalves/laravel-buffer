<?php

namespace JeffersonGoncalves\Buffer;

use JeffersonGoncalves\Buffer\Resources\ProfileResource;
use JeffersonGoncalves\Buffer\Resources\UpdateResource;
use JeffersonGoncalves\Buffer\Resources\UserResource;

/**
 * Entry point exposing one resource per Buffer API v1 group, plus the
 * single-call configuration endpoint.
 */
class Buffer
{
    protected BufferClient $client;

    public function __construct(string $baseUrl, string $accessToken)
    {
        $this->client = new BufferClient($baseUrl, $accessToken);
    }

    public function user(): UserResource
    {
        return new UserResource($this->client);
    }

    public function profiles(): ProfileResource
    {
        return new ProfileResource($this->client);
    }

    public function updates(): UpdateResource
    {
        return new UpdateResource($this->client);
    }

    /**
     * Configuration options for the Buffer API (supported services,
     * character/media limits, timezones, etc).
     */
    public function info(): array
    {
        return $this->client->get('/info/configuration.json');
    }
}
