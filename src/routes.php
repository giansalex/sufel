<?php
// Routes

use Sufel\App\Controllers\ClientController;
use Sufel\App\Controllers\CompanyController;
use Sufel\App\Controllers\DocumentController;
use Sufel\App\Controllers\HomeController;
use Sufel\App\Controllers\SecureController;

$app->group('/api/company', function () {
    /**@var $this \Slim\App*/
    $this->post('/', CompanyController::class . ':createCompany');
    $this->post('/auth', SecureController::class . ':company');
    $this->get('/documents', CompanyController::class . ':getInvoices');
    $this->post('/documents', CompanyController::class . ':addDocument');
    $this->patch('/documents', CompanyController::class . ':anularDocument');
    $this->post('/change-password', CompanyController::class . ':changePassword');
});

$app->group('/api/document', function () {
    /**@var $this \Slim\App*/
    $this->post('/auth', SecureController::class . ':client');
    $this->get('/resource/{type}', ClientController::class . ':getDocument');
});

$app->get('/', HomeController::class . ':home');
$app->get('/swagger', HomeController::class . ':swagger')->setName('swagger');
$app->get('/file/{hash}/{type}',DocumentController::class . ':download')->setName('file_download');
