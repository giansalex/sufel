<?php
// DIC configuration

use Sufel\App\Repository\CompanyRepository;
use Sufel\App\Repository\DbConnection;
use Sufel\App\Repository\DocumentRepository;
use Sufel\App\Service\AuthClient;
use Sufel\App\Service\CryptoService;
use Sufel\App\Service\LinkGenerator;
use Sufel\App\Utils\PdoErrorLogger;

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger =  new Katzgrau\KLogger\Logger($settings['path'], $settings['level'], ['extension' => 'log']);

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

$container[AuthClient::class] = function ($c) {
    return new AuthClient($c->get(\Sufel\App\Repository\ClienteRepository::class));
};
