<?php

namespace JeffersonGoncalves\Buffer\Resources;

use JeffersonGoncalves\Buffer\BufferClient;

class UpdateResource
{
    public function __construct(
        protected BufferClient $client,
    ) {}

    /**
     * A single update by its id.
     */
    public function get(string $id): array
    {
        return $this->client->get("/updates/{$id}.json");
    }

    /**
     * Updates still waiting to be sent for a profile.
     */
    public function pending(string $profileId, ?int $page = null, ?int $count = null, ?int $since = null): array
    {
        return $this->client->get("/profiles/{$profileId}/updates/pending.json", [
            'page' => $page,
            'count' => $count,
            'since' => $since,
        ]);
    }

    /**
     * Updates that have already been sent for a profile.
     */
    public function sent(string $profileId, ?int $page = null, ?int $count = null, ?int $since = null): array
    {
        return $this->client->get("/profiles/{$profileId}/updates/sent.json", [
            'page' => $page,
            'count' => $count,
            'since' => $since,
        ]);
    }

    /**
     * Create a new update for one or more profiles.
     *
     * @param  array<int, string>  $profileIds
     */
    public function create(
        array $profileIds,
        string $text,
        ?string $scheduledAt = null,
        bool $now = false,
        bool $top = false,
        bool $shorten = false,
    ): array {
        $body = [
            'profile_ids' => $profileIds,
            'text' => $text,
        ];

        if ($scheduledAt !== null) {
            $body['scheduled_at'] = $scheduledAt;
        }

        if ($now) {
            $body['now'] = 'true';
        }

        if ($top) {
            $body['top'] = 'true';
        }

        if ($shorten) {
            $body['shorten'] = 'true';
        }

        return $this->client->post('/updates/create.json', $body);
    }

    /**
     * Edit the text and/or scheduled time of a pending update.
     */
    public function update(string $id, string $text, ?string $scheduledAt = null): array
    {
        $body = ['text' => $text];

        if ($scheduledAt !== null) {
            $body['scheduled_at'] = $scheduledAt;
        }

        return $this->client->post("/updates/{$id}/update.json", $body);
    }

    /**
     * Immediately share a pending update, regardless of its scheduled time.
     */
    public function share(string $id): array
    {
        return $this->client->post("/updates/{$id}/share.json");
    }

    /**
     * Permanently delete a pending or sent update.
     */
    public function destroy(string $id): array
    {
        return $this->client->post("/updates/{$id}/destroy.json");
    }

    /**
     * Randomize the order of pending updates for a profile.
     */
    public function shuffle(string $profileId): array
    {
        return $this->client->post("/profiles/{$profileId}/updates/shuffle.json");
    }
}
