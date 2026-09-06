<div class="filament-hidden">

![Laravel Buffer](https://raw.githubusercontent.com/jeffersongoncalves/laravel-buffer/main/art/jeffersongoncalves-laravel-buffer.png)

</div>

# Laravel Buffer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-buffer.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-buffer)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-buffer/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-buffer/actions?query=workflow%3Atests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-buffer/pint.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-buffer/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-buffer.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-buffer)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-buffer.svg?style=flat-square)](LICENSE.md)

A Laravel client for the [Buffer](https://buffer.com) (bufferapp.com) REST API v1. Manage the authenticated user, connected social profiles, and pending/sent/scheduled updates through a simple, resource-based API, authenticated via a Bearer access token.

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-buffer
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag=buffer-config
```

This is the contents of the published config file:

```php
return [
    'access_token' => env('BUFFER_ACCESS_TOKEN', ''),
    'base_url' => env('BUFFER_BASE_URL', 'https://api.bufferapp.com/1'),
];
```

Add your Buffer access token to `.env`. Generate one at [buffer.com/developers/apps](https://buffer.com/developers/apps) by creating an app:

```bash
BUFFER_ACCESS_TOKEN=your-access-token
```

## Usage

All methods return the decoded JSON response as an `array`, or throw a `JeffersonGoncalves\Buffer\Exceptions\BufferException` when the Buffer API returns a failed HTTP response.

```php
use JeffersonGoncalves\Buffer\Facades\Buffer;
// or resolve the manager from the container: app(\JeffersonGoncalves\Buffer\Buffer::class)
```

### User

```php
// GET /user.json
$user = Buffer::user()->info();

// POST /user/deauthorize.json
Buffer::user()->deauthorize();
```

### Profiles

```php
// GET /profiles.json
$profiles = Buffer::profiles()->list();

// GET /profiles/{id}.json
$profile = Buffer::profiles()->get('4eb854340acb04e870000005');

// GET /profiles/{id}/schedules.json
$schedules = Buffer::profiles()->schedules('4eb854340acb04e870000005');
```

### Updates

```php
// GET /updates/{id}.json
$update = Buffer::updates()->get('4eb8ab585208912e5f000005');

// GET /profiles/{id}/updates/pending.json — page, count and since are optional
$pending = Buffer::updates()->pending('4eb854340acb04e870000005', page: 1, count: 10);

// GET /profiles/{id}/updates/sent.json
$sent = Buffer::updates()->sent('4eb854340acb04e870000005', since: now()->subWeek()->timestamp);

// POST /updates/create.json
Buffer::updates()->create(
    profileIds: ['4eb854340acb04e870000005'],
    text: 'Scheduled from Laravel!',
    scheduledAt: '2026-01-01 12:00:00',
    now: false,
    top: false,
    shorten: false,
);

// POST /updates/{id}/update.json
Buffer::updates()->update('4eb8ab585208912e5f000005', 'Updated text', '2026-01-02 09:00:00');

// POST /updates/{id}/share.json
Buffer::updates()->share('4eb8ab585208912e5f000005');

// POST /updates/{id}/destroy.json
Buffer::updates()->destroy('4eb8ab585208912e5f000005');

// POST /profiles/{id}/updates/shuffle.json
Buffer::updates()->shuffle('4eb854340acb04e870000005');
```

### API Configuration

```php
// GET /info/configuration.json
$config = Buffer::info();
```

### Handling errors

```php
use JeffersonGoncalves\Buffer\Exceptions\BufferException;
use JeffersonGoncalves\Buffer\Facades\Buffer;

try {
    Buffer::user()->info();
} catch (BufferException $e) {
    // $e->getMessage() — the Buffer API error message
    // $e->getCode() — the HTTP status code
    // $e->errorBody() — the full decoded error payload
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jèfferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
