<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/11/2017
 * Time: 12:47 PM
 */

namespace Sufel\App\Service;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Router;

/**
 * Class LinkGenerator
 * @package Sufel\App\Service
 */
class LinkGenerator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * LinkGenerator constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $data
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getLinks(array $data)
    {
        /**@var $router Router */
        $router = $this->container->get('router');
        $cryp = $this->container->get(CryptoService::class);
        $hash = urlencode($cryp->encrypt(json_encode($data)));

        $basePath = $this->getBasePath();

        $xmlLink = $router->pathFor('file_download', ['hash' => $hash, 'type' => 'xml']);
        $pdfLink = $router->pathFor('file_download', ['hash' => $hash, 'type' => 'pdf']);

        return [
          'xml' => $basePath . $xmlLink,
          'pdf' => $basePath . $pdfLink,
        ];
    }

    /**
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getFullBasePath()
    {
        $uri = $this->getUri();
        $url = $uri->getHost();
        if ($uri->getPort() && $uri->getPort() !== 80) {
            $url .= ':' . $uri->getPort();
        }
        /**@var $uri \Slim\Http\Uri */
        $url .= $uri->getBasePath();

        return $url;
    }

    /**
     * @return string Port with domain
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getBasePath()
    {
        $uri = $this->getUri();
        $url = $uri->getScheme().'://'.$uri->getHost();
        if ($uri->getPort() && $uri->getPort() !== 80) {
            $url .= ':' . $uri->getPort();
        }

        return $url;
    }

    /**
     * @return \Psr\Http\Message\UriInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getUri()
    {
        /**@var $request Request */
        $request = $this->container->get('request');
        return $request->getUri();
    }
}