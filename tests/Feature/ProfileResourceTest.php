<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Buffer\Facades\Buffer;

it('lists profiles', function () {
    Http::fake(['*/profiles.json' => Http::response([['id' => 'p1']])]);

    $result = Buffer::profiles()->list();

    expect($result[0]['id'])->toBe('p1');
});

it('gets a single profile', function () {
    Http::fake(['*/profiles/p1.json' => Http::response(['id' => 'p1'])]);

    $result = Buffer::profiles()->get('p1');

    expect($result['id'])->toBe('p1');
});

it('gets profile schedules', function () {
    Http::fake(['*/profiles/p1/schedules.json' => Http::response([['days' => ['mon']]])]);

    $result = Buffer::profiles()->schedules('p1');

    expect($result[0]['days'])->toBe(['mon']);
});
