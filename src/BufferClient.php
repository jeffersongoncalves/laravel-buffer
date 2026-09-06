<?php

namespace JeffersonGoncalves\Buffer;

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Buffer\Exceptions\BufferException;

/**
 * Thin wrapper around Laravel's Http client for the Buffer REST API v1.
 *
 * ponytail: Http::retry() already covers transient-failure retries if ever
 * needed later — no custom retry/backoff layer built here speculatively.
 */
class BufferClient
{
    public function __construct(
        protected string $baseUrl,
        protected string $accessToken,
    ) {}

    /** @param array<string, mixed> $query */
    public function get(string $path, array $query = []): array
    {
        return $this->request('get', $path, array_filter(
            $query,
            fn (mixed $value): bool => $value !== null
        ));
    }

    /** @param array<string, mixed> $body */
    public function post(string $path, array $body = []): array
    {
        return $this->request('post', $path, $body, asForm: true);
    }

    /** @param array<string, mixed> $data */
    protected function request(string $method, string $path, array $data = [], bool $asForm = false): array
    {
        $request = Http::baseUrl(rtrim($this->baseUrl, '/'))
            ->withToken($this->accessToken)
            ->acceptJson();

        if ($asForm) {
            $request = $request->asForm();
        }

        $response = $request->{$method}($path, $data);

        if ($response->failed()) {
            throw BufferException::fromResponse($response);
        }

        return (array) ($response->json() ?? []);
    }
}
