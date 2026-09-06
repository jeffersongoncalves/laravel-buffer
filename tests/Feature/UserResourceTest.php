<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Buffer\Facades\Buffer;

it('gets the authenticated user info', function () {
    Http::fake(['*/user.json' => Http::response(['id' => '4eb854340acb04e870000005', 'name' => 'Jane'])]);

    $result = Buffer::user()->info();

    expect($result['name'])->toBe('Jane');
    Http::assertSent(fn ($request) => $request->hasHeader('Authorization', 'Bearer test-access-token'));
});

it('deauthorizes the user', function () {
    Http::fake(['*/user/deauthorize.json' => Http::response(['success' => true])]);

    $result = Buffer::user()->deauthorize();

    expect($result['success'])->toBeTrue();
    Http::assertSent(fn ($request) => $request->method() === 'POST');
});
