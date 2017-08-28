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
    public function home($request, $response, $args) {
        $response->getBody()->write('<h1>Welcome to SUFEL API</h1>');

        return $response;
    }
}