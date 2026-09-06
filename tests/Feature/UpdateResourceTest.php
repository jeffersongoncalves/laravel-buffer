<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\Buffer\Facades\Buffer;

it('gets a single update', function () {
    Http::fake(['*/updates/u1.json' => Http::response(['id' => 'u1'])]);

    $result = Buffer::updates()->get('u1');

    expect($result['id'])->toBe('u1');
});

it('lists pending updates with query params', function () {
    Http::fake(['*/profiles/p1/updates/pending.json*' => Http::response(['updates' => []])]);

    Buffer::updates()->pending('p1', page: 2, count: 5);

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'page=2')
        && str_contains((string) $request->url(), 'count=5'));
});

it('omits null query params when listing pending updates', function () {
    Http::fake(['*/profiles/p1/updates/pending.json*' => Http::response(['updates' => []])]);

    Buffer::updates()->pending('p1');

    Http::assertSent(fn ($request) => ! str_contains((string) $request->url(), 'page=')
        && ! str_contains((string) $request->url(), 'count=')
        && ! str_contains((string) $request->url(), 'since='));
});

it('lists sent updates', function () {
    Http::fake(['*/profiles/p1/updates/sent.json*' => Http::response(['updates' => []])]);

    $result = Buffer::updates()->sent('p1', since: 1700000000);

    expect($result)->toBe(['updates' => []]);
    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'since=1700000000'));
});

it('creates an update', function () {
    Http::fake(['*/updates/create.json' => Http::response(['success' => true])]);

    Buffer::updates()->create(['p1', 'p2'], 'Hello world', now: true);

    Http::assertSent(fn ($request) => $request->method() === 'POST'
        && $request['text'] === 'Hello world'
        && $request['now'] === 'true'
        && $request['profile_ids'] === ['p1', 'p2']);
});

it('updates an existing update', function () {
    Http::fake(['*/updates/u1/update.json' => Http::response(['success' => true])]);

    Buffer::updates()->update('u1', 'Updated text', '2026-01-01 12:00:00');

    Http::assertSent(fn ($request) => $request['text'] === 'Updated text'
        && $request['scheduled_at'] === '2026-01-01 12:00:00');
});

it('shares an update now', function () {
    Http::fake(['*/updates/u1/share.json' => Http::response(['success' => true])]);

    Buffer::updates()->share('u1');

    Http::assertSent(fn ($request) => $request->method() === 'POST');
});

it('destroys an update', function () {
    Http::fake(['*/updates/u1/destroy.json' => Http::response(['success' => true])]);

    Buffer::updates()->destroy('u1');

    Http::assertSent(fn ($request) => $request->method() === 'POST');
});

it('shuffles pending updates for a profile', function () {
    Http::fake(['*/profiles/p1/updates/shuffle.json' => Http::response(['success' => true])]);

    Buffer::updates()->shuffle('p1');

    Http::assertSent(fn ($request) => $request->method() === 'POST');
});
