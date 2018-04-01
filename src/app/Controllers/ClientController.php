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
use Sufel\App\Utils\Validator;

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
}