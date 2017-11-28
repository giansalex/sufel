<?php
// DIC configuration

use Sufel\App\Repository\CompanyRepository;
use Sufel\App\Repository\DbConnection;
use Sufel\App\Repository\DocumentRepository;
use Sufel\App\Service\CryptoService;
use Sufel\App\Service\LinkGenerator;
use Sufel\App\Utils\PdoErrorLogger;

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    return $logger;
};

$container[CryptoService::class] = function ($c) {
    return new CryptoService($c->get('settings')['jwt']['secret']);
};

$container[LinkGenerator::class] = function ($c) {
    return new LinkGenerator($c);
};

$container[PdoErrorLogger::class] = function ($c) {
    return new PdoErrorLogger($c->get('logger'));
};

$container[DbConnection::class] = function ($c) {
    return new DbConnection($c);
};

$container[CompanyRepository::class] = function ($c) {
    return new CompanyRepository($c);
};

$container[DocumentRepository::class] = function ($c) {
    return new DocumentRepository($c->get(DbConnection::class));
};
