<?php

return [
    'cache' => [
        'search' => env('ANIME_CACHE_SEARCH', 86400),
        'episodes' => env('ANIME_CACHE_EPISODES', 86400),
        'embed' => env('ANIME_CACHE_EMBED', 21600),
        'stream' => env('ANIME_CACHE_STREAM', 3600),
    ],
    'per_page' => env('ANIME_PER_PAGE', 30),
    'base_url' => env('ANIME_BASE_URL', 'https://anidb.app'),
];