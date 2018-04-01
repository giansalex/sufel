<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 31/03/2018
 * Time: 23:37.
 */

namespace Sufel\App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Sufel\App\Service\ClientProfile;
use Sufel\App\Utils\Validator;

/**
 * Class ClientProfileController
 */
class ClientProfileController
{
    /**
     * @var ClientProfile
     */
    private $profile;

    /**
     * ClientProfileController constructor.
     *
     * @param ClientProfile $profile
     */
    public function __construct(ClientProfile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response $response
     * @param array $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function changePassword($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (!Validator::existFields($params, ['old', 'new', 'repeat'])) {
            return $response->withStatus(400);
        }
        $jwt = $request->getAttribute('jwt');
        list($success, $message) = $this->profile->changePassword(
            $jwt->document,
            $params['old'],
            $params['new'],
            $params['repeat']);

        if ($success === false) {
            return $response->withJson(['message' => empty($message) ? 'No se pudo cambiar la contraseña' : $message], 400);
        }

        return $response;
    }
}
