<?php

namespace JeffersonGoncalves\Buffer\Resources;

use JeffersonGoncalves\Buffer\BufferClient;

class ProfileResource
{
    public function __construct(
        protected BufferClient $client,
    ) {}

    /**
     * The social media profiles connected to the authenticated user.
     */
    public function list(): array
    {
        return $this->client->get('/profiles.json');
    }

    /**
     * A single social media profile by its id.
     */
    public function get(string $id): array
    {
        return $this->client->get("/profiles/{$id}.json");
    }

    /**
     * The posting schedules for a profile.
     */
    public function schedules(string $id): array
    {
        return $this->client->get("/profiles/{$id}/schedules.json");
    }
}
