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

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173',
        'http://localhost:5174',
        'http://localhost:3000',
        'http://localhost:8000',
        'https://bunna-coffee-platform.vercel.app',
        'https://bunna-coffee-platform-dh3ha9r0q-sophionasm-clouds-projects.vercel.app',
        'https://bunna-coffee-platform-auisw7384-sophionasm-clouds-projects.vercel.app',
    ],

    'allowed_origins_patterns' => [
        '#^https://bunna-coffee-platform.*\.vercel\.app$#', // ✅ Allows ALL future Vercel URLs automatically
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];