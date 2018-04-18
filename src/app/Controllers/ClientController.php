<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 28/08/2017
 * Time: 19:50.
 */

namespace Sufel\App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Sufel\App\Repository\ClienteRepository;
use Sufel\App\Repository\DocumentFilterRepository;
use Sufel\App\Repository\DocumentRepositoryInterface;
use Sufel\App\Repository\FileRepositoryInterface;
use Sufel\App\Utils\Validator;
use Sufel\App\ViewModels\FilterViewModel;

/**
 * Class ClientController.
 */
class ClientController
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * CompanyController constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getCompanies($request, $response, $args)
    {
        $jwt = $request->getAttribute('jwt');
        $document = $jwt->document;

        $repository = $this->container->get(ClienteRepository::class);
        $docs = $repository->getCompanies($document);

        return $response->withJson($docs);
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getList($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['start', 'end'])) {
            return $response->withStatus(400);
        }
        $init = new \DateTime($params['start']);
        $end = new \DateTime($params['end']);

        $jwt = $request->getAttribute('jwt');
        $document = $jwt->document;

        $filter = new FilterViewModel();
        $filter
            ->setClient($document)
            ->setEmisor(isset($params['emisor']) ? $params['emisor'] : '')
            ->setTipoDoc(isset($params['tipoDoc']) ? $params['tipoDoc'] : '')
            ->setSerie(isset($params['serie']) ? $params['serie'] : '')
            ->setCorrelativo(isset($params['correlativo']) ? $params['correlativo'] : '')
            ->setFecInicio($init)
            ->setFecFin($end);

        $repository = $this->container->get(DocumentFilterRepository::class);
        $docs = $repository->getList($filter);

        return $response->withJson($docs);
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDocument($request, $response, $args)
    {
        $id = $args['id'];
        $type = $args['type'];
        if (!in_array($type, ['info', 'xml', 'pdf'])) {
            return $response->withStatus(404);
        }

        $jwt = $request->getAttribute('jwt');
        $document = $jwt->document;

        $repository = $this->container->get(DocumentRepositoryInterface::class);
        $doc = $repository->getFile($id);
        if ($doc === null) {
            return $response->withStatus(404);
        }
        if ($doc['cliente_doc'] !== $document) {
            return $response->withStatus(401);
        }
        if ($type == 'info') {
            return $response->withJson($doc);
        }
        $fileRepo = $this->container->get(FileRepositoryInterface::class);

        $result = [];
        if ($type == 'xml') {
            $result['file'] = $fileRepo->getFile($id, 'xml');
            $result['type'] = 'text/xml';
        } else {
            $result['file'] = $fileRepo->getFile($id, 'pdf');
            $result['type'] = 'application/pdf';
        }
        $response->getBody()->write($result['file']);
        return $response
            ->withHeader('Content-Type', $result['type'])
            ->withHeader('Content-Disposition', 'attachment')
            ->withHeader('Content-Length', strlen($result['file']))
            ->withoutHeader('Pragma')
            ->withoutHeader('Expires')
            ->withoutHeader('Cache-Control');
    }
}
