<?php

declare(strict_types=1);

return [
    'default' => env('FIREBASE_PROJECT', 'app'),

    'projects' => [
        'qwilivery-7eb0f' => [
            'credentials' => [
                'type' => env('FIREBASE_CREDENTIALS_TYPE'),
                'project_id' => env('FIREBASE_CREDENTIALS_PROJECT_ID'),
                'private_key_id' => env('FIREBASE_CREDENTIALS_PRIVATE_KEY_ID'),
                'private_key' => env('FIREBASE_CREDENTIALS_PRIVATE_KEY'),
                'client_email' => env('FIREBASE_CREDENTIALS_CLIENT_EMAIL'),
                'client_id' => env('FIREBASE_CREDENTIALS_CLIENT_ID'),
                'auth_uri' => env('FIREBASE_CREDENTIALS_AUTH_URI'),
                'token_uri' => env('FIREBASE_CREDENTIALS_TOKEN_URI'),
                'auth_provider_x509_cert_url' => env('FIREBASE_CREDENTIALS_AUTH_PROVIDER_X509_CERT_URL'),
                'client_x509_cert_url' => env('FIREBASE_CREDENTIALS_CLIENT_X509_CERT_URL'),
                'universe_domain' => env('FIREBASE_CREDENTIALS_UNIVERSE_DOMAIN'),
            ],

            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],

            'firestore' => [
                // 'database' => env('FIREBASE_FIRESTORE_DATABASE'),
            ],

            'database' => [
                'url' => env('FIREBASE_DATABASE_URL'),
                // 'auth_variable_override' => [
                //     'uid' => 'my-service-worker'
                // ],
            ],

            'dynamic_links' => [
                'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
            ],

            'storage' => [
                'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),
            ],

            'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

            'logging' => [
                'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
                'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
            ],

            'http_client_options' => [
                'proxy' => env('FIREBASE_HTTP_CLIENT_PROXY'),
                'timeout' => env('FIREBASE_HTTP_CLIENT_TIMEOUT'),
                'guzzle_middlewares' => [],
            ],
        ],
    ],
];
