<?php

use GuzzleHttp\Psr7\Response as Psr7Response;
use Illuminate\Http\Client\Response;
use JeffersonGoncalves\Buffer\Exceptions\BufferException;

function fakeBufferResponse(int $status, array $body): Response
{
    return new Response(new Psr7Response($status, [], json_encode($body)));
}

it('builds the exception message from the response "error" field', function () {
    $response = fakeBufferResponse(401, ['error' => 'Missing or invalid access token.']);

    $exception = BufferException::fromResponse($response);

    expect($exception->getMessage())->toBe('Missing or invalid access token.')
        ->and($exception->getCode())->toBe(401)
        ->and($exception->errorBody())->toBe(['error' => 'Missing or invalid access token.']);
});

it('falls back to the "message" field when "error" is absent', function () {
    $response = fakeBufferResponse(404, ['message' => 'Not found.']);

    $exception = BufferException::fromResponse($response);

    expect($exception->getMessage())->toBe('Not found.');
});

it('falls back to a generic message when the body has no "error" or "message" key', function () {
    $response = fakeBufferResponse(500, []);

    $exception = BufferException::fromResponse($response);

    expect($exception->getMessage())->toBe('Buffer API error (HTTP 500).');
});
