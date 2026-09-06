<?php

use JeffersonGoncalves\Buffer\Buffer as BufferManager;
use JeffersonGoncalves\Buffer\Facades\Buffer;

it('merges the default config', function () {
    expect(config('buffer.base_url'))->toBe('https://api.bufferapp.com/1')
        ->and(config('buffer.access_token'))->toBe('test-access-token');
});

it('resolves the facade to the manager singleton', function () {
    expect(Buffer::getFacadeRoot())->toBeInstanceOf(BufferManager::class);
});

it('always resolves the same manager instance', function () {
    expect(app(BufferManager::class))->toBe(app(BufferManager::class));
});
