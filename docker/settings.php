<?php
return [
    'settings' => [
        'displayErrorDetails' => false, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs',
            'level' => Psr\Log\LogLevel::INFO,
        ],

        // Db Settings
        'db' => [
            'dsn' => 'mysql:host=' . getenv('SUFEL_DB_HOST') .';dbname=' . getenv('SUFEL_DB_DATABASE'),
            'user' => getenv('SUFEL_DB_USER'),
            'password' => getenv('SUFEL_DB_PASS'),
        ],
        // Jwt settings
        'jwt' => [
            'secret' => getenv('SUFEL_JWT_KEY'),
        ],

        // other settings
        'token_admin' => getenv('SUFEL_ADMIN'),
        'upload_dir' => 'upload',
    ],
];
