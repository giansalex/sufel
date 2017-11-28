<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/11/2017
 * Time: 12:42 PM
 */

namespace Sufel\App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Sufel\App\Repository\DocumentRepository;
use Sufel\App\Service\CryptoService;

/**
 * Class DocumentController
 * @package Sufel\App\Controllers
 */
class DocumentController
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * CompanyController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * @param ServerRequestInterface    $request
     * @param Response                  $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function download($request, $response, $args)
    {
        $hash = $args['hash'];
        $type = $args['type'];
        if (!in_array($type, ['xml', 'pdf'])) {
            return $response->withStatus(404);
        }

        $cryp = $this->container->get(CryptoService::class);
        $res = $cryp->decrypt($hash);
        if ($res === FALSE) {
            return $response->withStatus(404);
        }
        $obj = json_decode($res);
        $repo = $this->container->get(DocumentRepository::class);
        $doc = $repo->get($obj->id);
        if ($doc === null) {
            return $response->withStatus(404);
        }

        $name = $doc['filename'];
        $uploadDir = $this->container->get('settings')['upload_dir'];

        $pathZip = $uploadDir . DIRECTORY_SEPARATOR . $doc['emisor'] . DIRECTORY_SEPARATOR . $name . '.zip';
        $zip = new \ZipArchive();
        $zip->open($pathZip);

        $result = [];
        if ($type == 'xml') {
            $result['file'] = $zip->getFromName($name . '.xml');
            $result['type'] = 'application/xml';
        } else {
            $result['file'] = $zip->getFromName($name . '.pdf');
            $result['type'] = 'application/pdf';
        }
        $zip->close();

        $response->getBody()->write($result['file']);
        return $response
            ->withHeader('Content-Type', $result['type'])
            ->withHeader('Content-Disposition', "attachment; filename=\"$name.$type\";")
            ->withHeader('Content-Length', strlen($result['file']))
            ->withoutHeader('Pragma')
            ->withoutHeader('Expires')
            ->withoutHeader('Cache-Control');
    }

}