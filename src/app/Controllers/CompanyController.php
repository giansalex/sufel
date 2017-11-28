<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 28/08/2017
 * Time: 19:51
 */

namespace Sufel\App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Sufel\App\Models\Company;
use Sufel\App\Models\Document;
use Sufel\App\Models\Invoice;
use Sufel\App\Repository\CompanyRepository;
use Sufel\App\Repository\DocumentRepository;
use Sufel\App\Service\LinkGenerator;
use Sufel\App\Utils\Validator;
use Sufel\App\Utils\XmlExtractor;

/**
 * Class CompanyController
 * @package Sufel\App\Controllers
 */
class CompanyController
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * CompanyController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * @param ServerRequestInterface    $request
     * @param Response                  $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createCompany($request, $response, $args)
    {
        $queryParams = $request->getQueryParams();
        if (!isset($queryParams['token'])) {
            return $response->withStatus(400);
        }

        $adminToken = $this->container->get('settings')['token_admin'];
        if ($adminToken != $queryParams['token']) {
            return $response->withJson(['message' => 'invalid token'],401);
        }

        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['ruc', 'nombre', 'password'])) {
            return $response->withJson(['message' => 'parametros incompletos'],400);
        }

        $repo =  $this->container->get(CompanyRepository::class);
        if ($repo->exist($params['ruc'])) {
            return $response->withJson(['message' => 'Esta empresa ya esta registrada'],400);
        }

        $cp = new Company();
        $cp->setRuc($params['ruc'])
            ->setName($params['nombre'])
            ->setPassword($params['password'])
            ->setEnable(true);

        return $response->withStatus($repo->create($cp) ? 200 : 500);
    }

    /**
     * @param ServerRequestInterface    $request
     * @param Response                  $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function addDocument($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['xml', 'pdf'])) {
            return $response->withStatus(400);
        }

        $xml = base64_decode($params['xml']);
        $inv = $this->getInvoice($xml);
        $jwt = $request->getAttribute('jwt');
        if (empty($inv->getEmisor()) || $jwt->ruc != trim($inv->getEmisor())) {
            return $response->withJson(['message' => 'el ruc del emisor no coincide con el XML'], 400);
        }

        $repo =  $this->container->get(DocumentRepository::class);
        if ($repo->exist($inv)) {
            return $response->withJson(['message' => 'documento ya existe'], 400);
        }

        $name = join('-', [$inv->getEmisor(), $inv->getTipo(), $inv->getSerie(), $inv->getCorrelativo()]);
        $pdf = base64_decode($params['pdf']);

        $doc = new Document();
        $doc->setInvoice($inv)
            ->setFilename($name);
        $idSave = $repo->add($doc);

        if ($idSave === FALSE) {
            return $response->withStatus(500);
        }

        $rootDir = $this->container->get('settings')['upload_dir'] . DIRECTORY_SEPARATOR . $inv->getEmisor();
        if (!is_dir($rootDir)) {
            $oldmask = umask(0);
            mkdir($rootDir, 0777, true);
            umask($oldmask);
        }

        $path = $rootDir . DIRECTORY_SEPARATOR . $name . '.zip';
        $zip = new \ZipArchive();
        $zip->open($path, \ZipArchive::CREATE);
        $zip->addFromString($name.'.xml', $xml);
        $zip->addFromString($name.'.pdf', $pdf);
        $zip->close();

        $gen = $this->container->get(LinkGenerator::class);
        $links = $gen->getLinks(['id' => $idSave, 'ruc' => $inv->getEmisor()]);

        return $response->withJson($links);
    }

    /**
     * @param ServerRequestInterface    $request
     * @param Response                  $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function changePassword($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['old', 'new'])) {
            return $response->withStatus(400);
        }
        $jwt = $request->getAttribute('jwt');
        $repo = $this->container->get(CompanyRepository::class);
        $result = $repo->changePassword($jwt->ruc, $params['new'], $params['old']);
        if (!$result) {
            return $response->withJson(['message' => 'No se pudo cambiar la contraseña'], 400);
        }

        return $response;
    }

    /**
     * @param ServerRequestInterface    $request
     * @param Response                  $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function anularDocument($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['tipo', 'serie', 'correlativo'])) {
            return $response->withStatus(400);
        }
        $jwt = $request->getAttribute('jwt');
        $inv = new Invoice();
        $inv->setEmisor($jwt->ruc)
            ->setTipo($params['tipo'])
            ->setSerie($params['serie'])
            ->setCorrelativo($params['correlativo']);

        $repo = $this->container->get(DocumentRepository::class);
        $result = $repo->anular($inv);
        if (!$result) {
            return $response->withJson(['message' => 'No se pudo anular el documento'], 400);
        }

        return $response;
    }

    /**
     * @param ServerRequestInterface    $request
     * @param Response                  $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getInvoices($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['init', 'end'])) {
            return $response->withStatus(400);
        }
        $init = new \DateTime($params['init']);
        $end = new \DateTime($params['end']);

        $jwt = $request->getAttribute('jwt');
        $repo = $this->container->get(DocumentRepository::class);
        $result = $repo->getList($jwt->ruc, $init, $end);

        return $response->withJson($result);
    }

    /**
     * @param $xml
     * @return \Sufel\App\Models\Invoice
     */
    private function getInvoice($xml)
    {
        $doc = new \DOMDocument();
        @$doc->loadXML($xml);
        $ext = new XmlExtractor();

        return $ext->toInvoice($doc);
    }
}