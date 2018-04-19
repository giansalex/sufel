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
use Sufel\App\Models\ApiResult;
use Sufel\App\Service\AuthClient;
use Sufel\App\ViewModels\ClientRegister;

/**
 * Class ClientSecureController.
 */
class ClientSecureApi implements ClientSecureApiInterface
{
    use ResponseTrait;

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
     * Login client.
     *
     * @param string $document
     * @param string $password
     *
     * @return ApiResult
     */
    public function login($document, $password)
    {
        $service = $this->container->get(AuthClient::class);
        list($success) = $service->login($document, $password);
        if ($success === false) {
            return $this->response(400, ['message' => 'credenciales inválidas']);
        }

        $exp = strtotime('+5 hours');
        $data = [
            'scope' => ['client'],
            'document' => $document,
            'exp' => $exp,
        ];

        $token = JWT::encode($data, $this->secret);

        return $this->ok(['token' => $token, 'expire' => $exp]);
    }

    /**
     * Register or update client.
     *
     * @param ClientRegister $client
     *
     * @return ApiResult
     */
    public function register(ClientRegister $client)
    {
        $service = $this->container->get(AuthClient::class);
        list($success, $message) = $service->register($client);
        if ($success === false) {
            return $this->response(400, ['message' => empty($message) ? 'No se pudo registrar' : $message]);
        }

        return $this->ok(['message' => 'cliente registrado correctamente']);
    }
}
