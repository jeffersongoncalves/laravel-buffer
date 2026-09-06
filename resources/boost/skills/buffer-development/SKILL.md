---
name: buffer-development
description: Build and work with Laravel Buffer, a client for the Buffer (bufferapp.com) REST API v1 covering user, profiles, and updates.
---

# Buffer Development

## When to use this skill

Use this skill when:
- Working with the laravel-buffer package
- Adding a new Buffer API v1 endpoint
- Scheduling, editing, sharing, or deleting Buffer updates
- Writing tests that mock the Buffer API

## Core Concepts

### Entry point: the `Buffer` manager

`Buffer` (bound as a singleton and exposed via the `Buffer` facade) has one method per resource group, each returning a small resource object:

```php
use JeffersonGoncalves\Buffer\Facades\Buffer;

Buffer::user();      // UserResource
Buffer::profiles();  // ProfileResource
Buffer::updates();   // UpdateResource
Buffer::info();      // array — GET /info/configuration.json (no dedicated resource, single call)
```

### BufferClient

All resources share one `BufferClient` instance, which wraps Laravel's `Http` facade:
- `get(string $path, array $query = [])` — sends a GET request; `null` query values are stripped before the request.
- `post(string $path, array $body = [])` — sends a form-urlencoded POST (`asForm()`), matching what the Buffer API expects.
- Any failed response (`$response->failed()`) throws `BufferException`.

## Common Patterns

### Listing and fetching

```php
$profiles = Buffer::profiles()->list();
$profile = Buffer::profiles()->get($profiles[0]['id']);
$schedules = Buffer::profiles()->schedules($profile['id']);
```

### Paginated updates

`pending()` and `sent()` accept optional `page`, `count`, and `since` — omit any of them to leave the parameter out of the request entirely:

```php
Buffer::updates()->pending($profileId);                       // no query params
Buffer::updates()->pending($profileId, page: 2, count: 20);   // ?page=2&count=20
Buffer::updates()->sent($profileId, since: now()->subDay()->timestamp);
```

### Creating and editing updates

```php
Buffer::updates()->create(
    profileIds: [$profileId],
    text: 'Hello world',
    scheduledAt: '2026-01-01 12:00:00',
    now: false,
    top: false,
    shorten: false,
);

Buffer::updates()->update($updateId, 'New text', scheduledAt: null);
Buffer::updates()->share($updateId);
Buffer::updates()->destroy($updateId);
Buffer::updates()->shuffle($profileId);
```

`now`, `top`, and `shorten` are only sent (as the string `'true'`) when `true` — Buffer's API does not expect them otherwise.

## Troubleshooting

### Error: `BufferException` on every call

**Cause**: missing or invalid `BUFFER_ACCESS_TOKEN`.

**Solution**:
```php
try {
    Buffer::user()->info();
} catch (\JeffersonGoncalves\Buffer\Exceptions\BufferException $e) {
    report($e);
    // $e->errorBody() has the raw decoded Buffer error payload.
}
```

## API Reference

| Method | HTTP | Endpoint |
|--------|------|----------|
| `Buffer::user()->info()` | GET | `/user.json` |
| `Buffer::user()->deauthorize()` | POST | `/user/deauthorize.json` |
| `Buffer::profiles()->list()` | GET | `/profiles.json` |
| `Buffer::profiles()->get($id)` | GET | `/profiles/{id}.json` |
| `Buffer::profiles()->schedules($id)` | GET | `/profiles/{id}/schedules.json` |
| `Buffer::updates()->get($id)` | GET | `/updates/{id}.json` |
| `Buffer::updates()->pending($profileId, $page, $count, $since)` | GET | `/profiles/{id}/updates/pending.json` |
| `Buffer::updates()->sent($profileId, $page, $count, $since)` | GET | `/profiles/{id}/updates/sent.json` |
| `Buffer::updates()->create($profileIds, $text, $scheduledAt, $now, $top, $shorten)` | POST | `/updates/create.json` |
| `Buffer::updates()->update($id, $text, $scheduledAt)` | POST | `/updates/{id}/update.json` |
| `Buffer::updates()->share($id)` | POST | `/updates/{id}/share.json` |
| `Buffer::updates()->destroy($id)` | POST | `/updates/{id}/destroy.json` |
| `Buffer::updates()->shuffle($profileId)` | POST | `/profiles/{id}/updates/shuffle.json` |
| `Buffer::info()` | GET | `/info/configuration.json` |

**Returns**: `array` (decoded JSON body) for every method, or throws `BufferException` on a failed HTTP response.

## Testing Patterns

```php
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Buffer\Facades\Buffer;

it('lists profiles', function () {
    Http::fake(['*/profiles.json' => Http::response([['id' => 'p1']])]);

    $result = Buffer::profiles()->list();

    expect($result[0]['id'])->toBe('p1');
});
```

### Running Tests

```bash
vendor/bin/pest
vendor/bin/phpstan analyse
vendor/bin/pint
```
