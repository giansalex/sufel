<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 27/08/2017
 * Time: 14:11
 */

namespace Sufel\App\Controllers;

use Firebase\JWT\JWT;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Sufel\App\Repository\CompanyRepository;
use Sufel\App\Repository\DocumentRepository;
use Sufel\App\Utils\Validator;

/**
 * Class SecureController
 * @package Sufel\App\Controllers
 */
class SecureController
{
    /**
     * @var string
     */
    private $secret;

    protected $container;

    /**
     * SecureController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->secret = $container['settings']['jwt']['secret'];
        $this->container = $container;
    }

    /**
     * @param ServerRequestInterface    $request
     * @param Response                  $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function client($request, $response, $args) {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['emisor', 'tipo', 'serie', 'correlativo', 'fecha', 'total'])) {
            return $response->withStatus(400);
        }

        $repo = $this->container->get(DocumentRepository::class);
        $id = $repo->isAuthorized($params);
        if ($id === FALSE) {
            return $response->withStatus(401);
        }

        $exp = strtotime('+5 hours');
        $data = [
            'scope' => ['client'],
            'doc' => $id,
            'exp' => $exp,
        ];

        $token = JWT::encode($data, $this->secret);
        return $response->withJson(['token' => $token, 'exp' => $exp]);
    }

    /**
     * @param ServerRequestInterface    $request
     * @param Response                  $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function company($request, $response, $args) {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['ruc', 'password'])) {
            return $response->withStatus(400);
        }

        $repo = $this->container->get(CompanyRepository::class);
        $valid = $repo->isAuthorized($params['ruc'], $params['password']);
        if (!$valid) {
            return $response->withStatus(401);
        }

        $exp = strtotime('+2 days');
        $data = [
            'scope' => ['company'],
            'ruc' => $params['ruc'],
            'exp' => $exp,
        ];

        $token = JWT::encode($data, $this->secret);
        return $response->withJson(['token' => $token, 'exp' => $exp]);
    }
}