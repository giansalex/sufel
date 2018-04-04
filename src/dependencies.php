<?php
// DIC configuration

use Peru\Http\ClientInterface;
use Peru\Sunat\UserValidator;
use Sufel\App\Controllers\ClientProfileController;
use Sufel\App\Repository\ClienteRepository;
use Sufel\App\Repository\ClientProfileRepository;
use Sufel\App\Repository\CompanyRepository;
use Sufel\App\Repository\DbConnection;
use Sufel\App\Repository\DocumentRepository;
use Sufel\App\Service\AuthClient;
use Sufel\App\Service\ClientProfile;
use Sufel\App\Service\CryptoService;
use Sufel\App\Service\LinkGenerator;
use Sufel\App\Service\UserValidateInterface;
use Sufel\App\Utils\PdoErrorLogger;

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger =  new Katzgrau\KLogger\Logger($settings['path'], $settings['level'], ['extension' => 'log']);

    return $logger;
};

$container[ClientInterface::class] = function () {
    return new \Peru\Http\ContextClient();
};

$container[UserValidator::class] = function ($c) {
    return new UserValidator($c->get(ClientInterface::class));
};

$container[UserValidateInterface::class] = function ($c) {
    return new \Sufel\App\Service\UserValidatorAdapter($c->get(UserValidator::class));
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
    return new AuthClient(
        $c->get(ClienteRepository::class),
        $c->get(ClientProfileRepository::class),
        $c->get(UserValidateInterface::class)
    );
};

$container[ClientProfile::class] = function ($c) {
    return new ClientProfile($c->get(ClienteRepository::class), $c->get(ClientProfileRepository::class));
};

$container[ClientProfileController::class] = function ($c) {
    return new ClientProfileController($c->get(ClientProfile::class));
};
