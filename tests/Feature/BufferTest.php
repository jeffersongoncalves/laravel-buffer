<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Buffer\Exceptions\BufferException;
use JeffersonGoncalves\Buffer\Facades\Buffer;

it('gets the api configuration info', function () {
    Http::fake(['*/info/configuration.json' => Http::response(['photo_video_countries' => []])]);

    $result = Buffer::info();

    expect($result)->toHaveKey('photo_video_countries');
});

it('throws a BufferException on a failed request', function () {
    Http::fake(['*/user.json' => Http::response(['error' => 'Missing or invalid access token.'], 401)]);

    Buffer::user()->info();
})->throws(BufferException::class, 'Missing or invalid access token.');
