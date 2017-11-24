<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 26/08/2017
 * Time: 18:25
 */

namespace Sufel\App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class HomeController
 * @package Sufel\App\Controllers
 */
class HomeController
{
    /**
     * @param ServerRequestInterface    $request
     * @param ResponseInterface         $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function home($request, $response, $args)
    {
        $swaggerUrl = $request->getUri() . 'swagger';
        $body = <<<HTML
<h1>Welcome to SUFEL API</h1>
<a href="http://petstore.swagger.io/?url=$swaggerUrl">Swagger API documentacion</a>
HTML;

        $response->getBody()->write($body);

        return $response;
    }

    /**
     * @param ServerRequestInterface    $request
     * @param ResponseInterface         $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function swagger($request, $response, $args)
    {
        $filename = __DIR__ . '/../../data/swagger.json';
        if (!file_exists($filename)) {
            return $response->withStatus(404);
        }
        $jsonContent = file_get_contents($filename);
        $response->getBody()->write(str_replace('sufel.net', $this->getBaseUri($request), $jsonContent));

        return $response->withHeader('Content-Type', 'application/json; charset=utf8');
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    private function getBaseUri($request)
    {
        $uri = $request->getUri();
        $url = $uri->getHost();
        if ($uri->getPort() && $uri->getPort() !== 80) {
            $url .= ':' . $uri->getPort();
        }
        /**@var $uri \Slim\Http\Uri */
        $url .= $uri->getBasePath();

        return $url;
    }
}