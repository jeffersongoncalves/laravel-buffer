<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Buffer Access Token
    |--------------------------------------------------------------------------
    |
    | Find it at https://buffer.com/developers/apps by creating an app and
    | generating an access token for it. Sent as a Bearer token on every
    | request.
    |
    */
    'access_token' => env('BUFFER_ACCESS_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Buffer API Base URL
    |--------------------------------------------------------------------------
    */
    'base_url' => env('BUFFER_BASE_URL', 'https://api.bufferapp.com/1'),

];
