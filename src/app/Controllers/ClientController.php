<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 28/08/2017
 * Time: 19:50
 */

namespace Sufel\App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Sufel\App\Repository\DocumentRepository;

/**
 * Class ClientController
 * @package Sufel\App\Controllers
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
    public function getDocument($request, $response, $args)
    {
        $params = $request->getQueryParams();
        if (!isset($params['query'])) {
            return $response->withStatus(404);
        }
        $reqs = explode(',', (string)$params['query']);

        $jwt = $request->getAttribute('jwt');
        $id = $jwt->doc;

        $doc = $this->repository->get($id);
        if ($doc === null) {
            return $response->withStatus(404);
        }

        $name = $doc['filename'];
        $path = $this->rootDir . DIRECTORY_SEPARATOR . $doc['emisor'] . DIRECTORY_SEPARATOR . $name;

        if (!in_array('info', $reqs)) {
            $doc = [];
        }

        if (in_array('xml', $reqs)) {
            $doc['xml'] = base64_encode(file_get_contents($path . '.xml'));
        }

        if (in_array('pdf', $reqs)) {
            $doc['pdf'] = base64_encode(file_get_contents($path . '.pdf'));
        }

        return $response->withJson($doc);
    }
}