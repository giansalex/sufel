<?php
// DIC configuration

use Sufel\App\Repository\CompanyRepository;
use Sufel\App\Repository\DbConnection;
use Sufel\App\Repository\DocumentRepository;

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    return $logger;
};

$container[DbConnection::class] = function ($c) {
    return new DbConnection($c->get('settings')['db']);
};

$container[CompanyRepository::class] = function ($c) {
    return new CompanyRepository($c);
};

$container[DocumentRepository::class] = function ($c) {
    return new DocumentRepository($c->get(DbConnection::class));
};
