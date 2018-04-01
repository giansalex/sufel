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
use Sufel\App\Repository\DocumentRepository;
use Sufel\App\Utils\Validator;

/**
 * Class ClientController.
 */
class ClientController
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
     *
     * @param ContainerInterface $container
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get(DocumentRepository::class);
        $this->rootDir = $container->get('settings')['upload_dir'];
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getList($request, $response, $args)
    {
        $params = $request->getQueryParams();
        if (!Validator::existFields($params, ['start', 'end'])) {
            return $response->withStatus(400);
        }
        $init = new \DateTime($params['start']);
        $end = new \DateTime($params['end']);

        $jwt = $request->getAttribute('jwt');
        $document = $jwt->document;

        $docs = $this->repository->getListByClient($document, $init, $end);

        return $response->withJson($docs);
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
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

        $doc = $this->repository->get($id);
        if ($doc === null) {
            return $response->withStatus(404);
        }

        if ($doc['cliente_doc'] !== $document) {
            return $response->withStatus(401);
        }

        if ($type == 'info') {
            return $response->withJson($doc);
        }

        $name = $doc['filename'];
        $pathZip = $this->rootDir.DIRECTORY_SEPARATOR.$doc['emisor'].DIRECTORY_SEPARATOR.$name.'.zip';
        $zip = new \ZipArchive();
        $zip->open($pathZip);

        $result = [];
        if ($type == 'xml') {
            $result['file'] = $zip->getFromName($name.'.xml');
            $result['type'] = 'text/xml';
        } else {
            $result['file'] = $zip->getFromName($name.'.pdf');
            $result['type'] = 'application/pdf';
        }
        $zip->close();

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
