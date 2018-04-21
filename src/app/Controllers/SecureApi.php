<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 27/08/2017
 * Time: 14:11.
 */

namespace Sufel\App\Controllers;

use Firebase\JWT\JWT;
use Sufel\App\Models\ApiResult;
use Sufel\App\Repository\CompanyRepositoryInterface;
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
    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;
    /**
     * @var CompanyRepositoryInterface
     */
    private $companyRepository;

    /**
     * SecureApi constructor.
     *
     * @param string $secret
     * @param DocumentRepositoryInterface $documentRepository
     * @param CompanyRepositoryInterface  $companyRepository
     */
    public function __construct(
        $secret,
        DocumentRepositoryInterface $documentRepository,
        CompanyRepositoryInterface $companyRepository
    ) {
        $this->secret = $secret;
        $this->documentRepository = $documentRepository;
        $this->companyRepository = $companyRepository;
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
        $id = $this->documentRepository->isAuthorized($login);
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
        $valid = $this->companyRepository->isAuthorized($ruc, $password);

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
