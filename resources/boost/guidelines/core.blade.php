## Laravel Buffer

This package provides a Laravel-friendly client for the Buffer (bufferapp.com) REST API v1: user info, connected social profiles, and pending/sent/scheduled updates.

**Namespace:** `JeffersonGoncalves\Buffer`
**Service Provider:** `BufferServiceProvider` (auto-discovered)
**Facade:** `Buffer` (`JeffersonGoncalves\Buffer\Facades\Buffer`)

### Installation

@verbatim
<code-snippet name="Install the package" lang="bash">
composer require jeffersongoncalves/laravel-buffer
</code-snippet>
@endverbatim

Set your Buffer access token in `.env`:

@verbatim
<code-snippet name="Environment variables" lang="bash">
BUFFER_ACCESS_TOKEN=your-access-token
</code-snippet>
@endverbatim

### Features

- **Resource-based API**: `Buffer::user()`, `Buffer::profiles()`, and `Buffer::updates()` each return a dedicated resource with one method per endpoint.
- **Bearer authentication**: every request is sent with the configured access token.
- **Typed exceptions**: failed HTTP responses throw `BufferException` with the API error message and status code.

@verbatim
<code-snippet name="Basic usage" lang="php">
use JeffersonGoncalves\Buffer\Facades\Buffer;

$profiles = Buffer::profiles()->list();

Buffer::updates()->create(
    profileIds: [$profiles[0]['id']],
    text: 'Scheduled from Laravel!',
);
</code-snippet>
@endverbatim

### Configuration

@verbatim
<code-snippet name="Config example" lang="php">
// config/buffer.php
return [
    'access_token' => env('BUFFER_ACCESS_TOKEN', ''),
    'base_url' => env('BUFFER_BASE_URL', 'https://api.bufferapp.com/1'),
];
</code-snippet>
@endverbatim

### Best Practices

- Always wrap calls in a `try`/`catch (BufferException $e)` block when the user's access token might be invalid or expired.
- Use `Http::fake()` in tests instead of hitting the real Buffer API.
