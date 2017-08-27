<?php
// Routes

use FeConsult\App\Controllers\HomeController;
use FeConsult\App\Controllers\SecureController;

$app->group('/api/company', function () {
    /**@var $this \Slim\App*/
    $this->post('/auth', SecureController::class . ':company');
    $this->get('/list', function ($request, $response, $args) {
        $response->getBody()->write('You company are authorized');
        return $response;
    });
});

$app->group('/api/client', function () {
    /**@var $this \Slim\App*/
    $this->post('/auth', SecureController::class . ':client');
    $this->get('/list', function ($request, $response, $args) {
        $response->getBody()->write('You are client authorized');
        return $response;
    });
});

$app->get('/', HomeController::class . ':home');