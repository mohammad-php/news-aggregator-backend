<?php

declare(strict_types=1);

return [
    'newsapi' => [
        'api_key' => env('NEWSAPI_KEY'),
        'base_url' => 'https://newsapi.org/v2',
        'endpoint'  => '/top-headlines',
        'page_size' => 50,
    ],

    'guardian' => [
        'api_key' => env('GUARDIAN_KEY'),
        'base_url' => 'https://content.guardianapis.com',
        'endpoint' => '/search',
        'page_size' => 50,
        'max_pages' => 5,
    ],

    'nytimes' => [
        'api_key'  => env('NYT_API_KEY'),
        'base_url' => 'https://api.nytimes.com/svc/topstories/v2/',
        'sections' => [
            'home',
            'world',
            'us',
            'business',
            'technology',
        ],
    ],
];
