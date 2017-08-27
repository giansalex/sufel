<?php
// Routes

use FeConsult\App\Controllers\HomeController;


$app->group('/api', function () {
    /**@var $this \Slim\App*/

})->add(new \FeConsult\App\Middlewares\TokenMiddleware());

$app->get('/', HomeController::class . ':home');