<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:3000'),
        // Production Domains
        'https://wsa-network.com',
        'https://wsa-elite.com',
        'https://wsa-consol.com',
        'https://test.wsa-events.com',
        'https://wsa-events.com',

        // Local Dev domains
        'http://events-nuxt.test:4224',
        'http://wsa-network.test:8081',
        'http://wsa-elite.test:8082',
        'http://wsa-consol.test:8083',
        ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
