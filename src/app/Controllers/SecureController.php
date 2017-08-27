<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 27/08/2017
 * Time: 14:11
 */

namespace FeConsult\App\Controllers;

use Firebase\JWT\JWT;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

/**
 * Class SecureController
 * @package FeConsult\App\Controllers
 */
class SecureController
{
    /**
     * @var string
     */
    private $secret;

    /**
     * SecureController constructor.
     * @param $secret
     */
    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @param ServerRequestInterface    $request
     * @param Response                  $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function client($request, $response, $args) {
        $params = $request->getParsedBody();
        // verify in database by document.

        if (!isset($params['user'])) {
            return $response->withStatus(400);
        }
        $exp = strtotime('+5 hours');

        $data = [
            'scope' => ['client'],
            'doc' => 'identifier',
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
        // verify in database company.

        $exp = strtotime('+2 days');

        $data = [
            'scope' => ['company'],
            'doc' => 'company_id',
            'exp' => $exp,
        ];

        $token = JWT::encode($data, $this->secret);
        return $response->withJson(['token' => $token, 'exp' => $exp]);
    }
}