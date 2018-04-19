<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 27/08/2017
 * Time: 14:11.
 */

namespace Sufel\App\Controllers;

use Firebase\JWT\JWT;
use Psr\Container\ContainerInterface;
use Sufel\App\Models\ApiResult;
use Sufel\App\Repository\CompanyRepository;
use Sufel\App\Repository\DocumentRepositoryInterface;
use Sufel\App\ViewModels\DocumentLogin;

/**
 * Class SecureApi.
 */
class SecureApi implements SecureApiInterface
{
    use ResponseTrait;

    /**
     * @var string
     */
    private $secret;

    protected $container;

    /**
     * SecureApi constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->secret = $container['settings']['jwt']['secret'];
        $this->container = $container;
    }

    /**
     * Login by document.
     *
     * @param DocumentLogin $login
     *
     * @return ApiResult
     */
    public function client(DocumentLogin $login)
    {
        $repo = $this->container->get(DocumentRepositoryInterface::class);
        $id = $repo->isAuthorized($login);
        if ($id === false) {
            return $this->response(404, ['message' => 'documento no encontrado']);
        }

        $exp = strtotime('+5 hours');
        $data = [
            'scope' => ['document'],
            'doc' => $id,
            'exp' => $exp,
        ];

        $token = JWT::encode($data, $this->secret);

        return $this->ok(['token' => $token, 'expire' => $exp]);
    }

    /**
     * Login by company.
     *
     * @param string $ruc
     * @param string $password
     *
     * @return ApiResult
     */
    public function company($ruc, $password)
    {
        $repo = $this->container->get(CompanyRepository::class);
        $valid = $repo->isAuthorized($ruc, $password);

        if (!$valid) {
            return $this->response(400, ['message' => 'credenciales inválidas']);
        }

        $exp = strtotime('+2 days');
        $data = [
            'scope' => ['company'],
            'ruc' => $ruc,
            'exp' => $exp,
        ];

        $token = JWT::encode($data, $this->secret);

        return $this->ok(['token' => $token, 'expire' => $exp]);
    }
}
