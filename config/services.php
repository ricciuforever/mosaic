<?php

return [
    'gemini' => [
        // Letta esclusivamente a runtime da variabile d'ambiente server (.env produzione).
        // MAI salvare GEMINI_API_KEY in file locali o repository.
        'api_key' => env('GEMINI_API_KEY', null),
        'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
        'endpoint' => 'https://generativelanguage.googleapis.com/v1beta/models/',
        'timeout' => 12,
    ],
];
