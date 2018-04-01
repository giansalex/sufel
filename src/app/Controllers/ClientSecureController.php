<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 31/03/2018
 * Time: 22:58.
 */

namespace Sufel\App\Controllers;

use Firebase\JWT\JWT;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Sufel\App\Service\AuthClient;
use Sufel\App\Utils\Validator;
use Sufel\App\ViewModels\ClientRegister;

/**
 * Class ClientSecureController.
 */
class ClientSecureController
{
    /**
     * @var string
     */
    private $secret;

    protected $container;

    /**
     * SecureController constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->secret = $container['settings']['jwt']['secret'];
        $this->container = $container;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response $response
     * @param array $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function login($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['documento', 'password'])) {
            return $response->withStatus(400);
        }

        $service = $this->container->get(AuthClient::class);
        list($success) = $service->login($params['documento'], $params['password']);
        if ($success === false) {
            return $response->withJson(['message' => 'credenciales inválidas'], 400);
        }

        $exp = strtotime('+5 hours');
        $data = [
            'scope' => ['client'],
            'document' => $params['documento'],
            'exp' => $exp,
        ];

        $token = JWT::encode($data, $this->secret);

        return $response->withJson(['token' => $token, 'expire' => $exp]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response $response
     * @param array $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function register($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['documento', 'nombres', 'usuario_sol', 'password', 'repeat_password'])) {
            return $response->withStatus(400);
        }

        $client = new ClientRegister();
        $client->setDocumento($params['documento'])
            ->setNombres($params['nombres'])
            ->setUserSol($params['usuario_sol'])
            ->setPassword($params['password'])
            ->setRepeatPassword($params['repeat_password']);

        $service = $this->container->get(AuthClient::class);
        list($success, $message) = $service->register($client);
        if ($success === false) {
            return $response->withJson(['message' => empty($message) ? 'No se pudo registrar' : $message], 400);
        }

        return $response->withJson(['message' => 'registrado exitosamente']);
    }
}
