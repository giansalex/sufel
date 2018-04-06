<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 26/08/2017
 * Time: 18:25
 */

namespace Sufel\App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Router;
use Sufel\App\Service\LinkGenerator;

/**
 * Class HomeController
 * @package Sufel\App\Controllers
 */
class HomeController
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * HomeController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function home($request, $response, $args)
    {
        $gen = $this->container->get(LinkGenerator::class);
        /**@var $router Router */
        $router = $this->container->get('router');

        $swaggerUrl = $gen->getBasePath() . $router->pathFor('swagger');
        $body = <<<HTML
<h1>Welcome to SUFEL API</h1>
<a href="http://petstore.swagger.io/?url=$swaggerUrl">Swagger API Full</a><br>
<a href="http://petstore.swagger.io/?url=$swaggerUrl%3Ffor%3Dcompany">Swagger API for Company</a><br>
<a href="http://petstore.swagger.io/?url=$swaggerUrl%3Ffor%3Dreceiver">Swagger API for Receiver</a>
HTML;

        $response->getBody()->write($body);

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function swagger($request, $response, $args)
    {
        $type = $request->getQueryParams()['for'];

        $name = 'swagger';
        if ($type &&
            in_array(strtolower($type),['company', 'receiver'])) {
            $name .= '.'.strtolower($type);
        }

        $filename = __DIR__ . "/../../data/$name.json";
        if (!file_exists($filename)) {
            return $response->withStatus(404);
        }
        $jsonContent = file_get_contents($filename);
        $gen = $this->container->get(LinkGenerator::class);
        $response->getBody()->write(str_replace('sufel.net', $gen->getFullBasePath(), $jsonContent));

        return $response->withHeader('Content-Type', 'application/json; charset=utf8');
    }
}