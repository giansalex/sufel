<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Logger settings
        'logger' => [
            'name' => 'slim-app',
            'path' => 'php://stderr',
            'level' => Psr\Log\LogLevel::DEBUG,
        ],

        // Db Settings
        'db' => [
            'dsn' => 'mysql:host=127.0.0.1;dbname=sufel_dev',
            'user' => 'root',
            'password' => '',
        ],
        // Jwt settings
        'jwt' => [
            'secret' => 'yYa3Nmalk1a56fhA',
        ],

        // other settings
        'token_admin' => 'jsAkl34Oa2Tyu',
        'upload_dir' => __DIR__.'/../public/upload',
    ],
];
