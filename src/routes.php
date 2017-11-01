<?php
// Routes

use Sufel\App\Controllers\ClientController;
use Sufel\App\Controllers\CompanyController;
use Sufel\App\Controllers\HomeController;
use Sufel\App\Controllers\SecureController;

$app->group('/api/company', function () {
    /**@var $this \Slim\App*/
    $this->post('/auth', SecureController::class . ':company');
    $this->post('/add-document', CompanyController::class . ':addDocument');
    $this->post('/create', CompanyController::class . ':createCompany');
    $this->post('/change-password', CompanyController::class . ':changePassword');
});

$app->group('/api/client', function () {
    /**@var $this \Slim\App*/
    $this->post('/auth', SecureController::class . ':client');
    $this->get('/document/{type}', ClientController::class . ':getDocument');
});

$app->get('/', HomeController::class . ':home');
$app->get('/swagger', HomeController::class . ':swagger');