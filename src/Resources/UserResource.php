<?php

namespace JeffersonGoncalves\Buffer\Resources;

use JeffersonGoncalves\Buffer\BufferClient;

class UserResource
{
    public function __construct(
        protected BufferClient $client,
    ) {}

    /**
     * The authenticated user's details.
     */
    public function info(): array
    {
        return $this->client->get('/user.json');
    }

    /**
     * Revoke the current access token, disconnecting the app from the user.
     */
    public function deauthorize(): array
    {
        return $this->client->post('/user/deauthorize.json');
    }
}
