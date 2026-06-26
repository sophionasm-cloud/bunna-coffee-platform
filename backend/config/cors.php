<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173',
        'http://localhost:5174',
        'http://localhost:3000',
        'http://localhost:8000',
        'https://bunna-coffee-platform.vercel.app',
    ],

    'allowed_origins_patterns' => [
        '#^https://bunna-coffee-platform.*\.vercel\.app$#',
    ],

    'allowed_headers' => [
        'Content-Type',
        'Authorization',      // ✅ Explicitly allow Authorization
        'Accept',
        'X-Requested-With',
        'X-XSRF-TOKEN',
    ],

    'exposed_headers' => ['Authorization'],

    'max_age' => 86400,

    'supports_credentials' => true,
];