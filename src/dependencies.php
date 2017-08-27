<?php
// DIC configuration

use FeConsult\App\Controllers\SecureController;

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container[SecureController::class] = function($c) {
    return new SecureController($c['settings']['jwt']['secret']);
};
