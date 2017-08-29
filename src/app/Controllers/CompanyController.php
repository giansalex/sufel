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
use Sufel\App\Models\Document;
use Sufel\App\Repository\DocumentRepository;
use Sufel\App\Utils\Validator;
use Sufel\App\Utils\XmlExtractor;

/**
 * Class CompanyController
 * @package Sufel\App\Controllers
 */
class CompanyController
{
    /**
     * @var DocumentRepository
     */
    private $repository;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * CompanyController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->repository = $container->get(DocumentRepository::class);
        $this->rootDir = $container->get('settings')['upload_dir'];
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
        if ($this->repository->exist($inv)) {
            return $response->withJson(['message' => 'documento ya existe'], 400);
        }

        $name = join('-', [$inv->getEmisor(), $inv->getTipo(), $inv->getSerie(), $inv->getCorrelativo()]);
        $pdf = base64_decode($params['pdf']);
        $jwt = $request->getAttribute('jwt');

        $inv->setEmisor($jwt->ruc);
        $doc = new Document();
        $doc->setInvoice($inv)
            ->setFilename($name);
        $save = $this->repository->add($doc);
        if (!$save) {
            return $response->withStatus(500);
        }

        $path = $this->rootDir . DIRECTORY_SEPARATOR . $inv->getEmisor() . DIRECTORY_SEPARATOR . $name;
        file_put_contents($path.'.xml', $xml);
        file_put_contents($path.'.pdf', $pdf);

        return $response;
    }

    /**
     * @param $xml
     * @return \Sufel\App\Models\Invoice
     */
    private function getInvoice($xml)
    {
        $doc = new \DOMDocument();
        @$doc->load($xml);
        $ext = new XmlExtractor();

        return $ext->toInvoice($doc);
    }
}