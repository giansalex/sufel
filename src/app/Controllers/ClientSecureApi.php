<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 31/03/2018
 * Time: 22:58.
 */

namespace Sufel\App\Controllers;

use Sufel\App\Models\ApiResult;
use Sufel\App\Service\AuthClient;
use Sufel\App\Service\TokenServiceInterface;
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
    /**
     * @var AuthClient
     */
    private $authClient;
    /**
     * @var TokenServiceInterface
     */
    private $tokenService;

    /**
     * ClientSecureApi constructor.
     *
     * @param string $secret
     * @param AuthClient $authClient
     * @param TokenServiceInterface $tokenService
     */
    public function __construct($secret, AuthClient $authClient, TokenServiceInterface $tokenService)
    {
        $this->secret = $secret;
        $this->authClient = $authClient;
        $this->tokenService = $tokenService;
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
        list($success) = $this->authClient->login($document, $password);
        if ($success === false) {
            return $this->response(400, ['message' => 'credenciales inválidas']);
        }

        $exp = strtotime('+5 hours');
        $data = [
            'scope' => ['client'],
            'document' => $document,
            'exp' => $exp,
        ];

        $token = $this->tokenService->create($data, $this->secret);

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
        list($success, $message) = $this->authClient->register($client);
        if ($success === false) {
            return $this->response(400, ['message' => empty($message) ? 'No se pudo registrar' : $message]);
        }

        return $this->ok(['message' => 'cliente registrado correctamente']);
    }
}
