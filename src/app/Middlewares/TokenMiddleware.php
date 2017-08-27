<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 26/08/2017
 * Time: 18:05
 */

namespace Sufel\App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

/**
 * Class TokenMiddleware
 * @package Sufel\App\Middlewares
 */
class TokenMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequestInterface $request  PSR7 request
     * @param  ResponseInterface      $response PSR7 response
     * @param  callable               $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $header = $request->getHeader('Authorization');
        if (empty($header)) {
            /**@var $response Response */

            return $response->withJson(['message' => 'usted no esta autorizado'], 401);
        }

        $response = $next($request, $response);
        return $response;
    }
}