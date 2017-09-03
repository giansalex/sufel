<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // Db Settings
        'db' => [
            'dsn' => 'mysql:host=' . getenv('SUFEL_DB_HOST') .';dbname=' . getenv('SUFEL_DB_DATABASE'),
            'user' => getenv('SUFEL_DB_USER'),
            'password' => getenv('SUFEL_DB_PASS'),
        ],
        // Jwt settings
        'jwt' => [
            'secret' => 'yYa3Nmalk1a56fhA',
        ],

        // other settings
        'token_admin' => 'jsAkl34Oa2Tyu',
        'upload_dir' => 'upload',
    ],
];
