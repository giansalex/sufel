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
            'dsn' => 'mysql:host=127.0.0.1;dbname=sufel',
            'user' => 'root',
            'password' => '',
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
