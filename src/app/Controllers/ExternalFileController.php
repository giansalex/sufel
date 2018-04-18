<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 31/03/2018
 * Time: 22:40.
 */

namespace Sufel\App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Sufel\App\Repository\DocumentRepositoryInterface;
use Sufel\App\Repository\FileRepositoryInterface;
use Sufel\App\Service\CryptoService;

/**
 * Class ExternalFileController.
 */
class ExternalFileController
{
    /**
     * @var CryptoService
     */
    private $crypto;
    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;
    /**
     * @var FileRepositoryInterface
     */
    private $fileRepository;

    /**
     * ExternalFileController constructor.
     *
     * @param CryptoService               $crypto
     * @param DocumentRepositoryInterface $documentRepository
     * @param FileRepositoryInterface     $fileRepository
     */
    public function __construct(
        CryptoService $crypto,
        DocumentRepositoryInterface $documentRepository,
        FileRepositoryInterface $fileRepository
    ) {
        $this->crypto = $crypto;
        $this->documentRepository = $documentRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function download($request, $response, $args)
    {
        $hash = $args['hash'];
        $type = $args['type'];
        if (!in_array($type, ['xml', 'pdf'])) {
            return $response->withStatus(404);
        }

        $res = $this->crypto->decrypt($hash);
        if ($res === false) {
            return $response->withStatus(404);
        }
        $obj = json_decode($res);
        $id = $obj->id;
        $doc = $this->documentRepository->get($id);
        if ($doc === null) {
            return $response->withStatus(404);
        }

        $result = [];
        if ($type == 'xml') {
            $result['file'] = $this->fileRepository->getFile($id, 'xml');
            $result['type'] = 'text/xml';
        } else {
            $result['file'] = $this->fileRepository->getFile($id, 'pdf');
            $result['type'] = 'application/pdf';
        }

        $response->getBody()->write($result['file']);

        return $response
            ->withHeader('Content-Type', $result['type'])
            ->withHeader('Content-Disposition', "attachment; filename=\"{$doc['filename']}.$type\";")
            ->withHeader('Content-Length', strlen($result['file']))
            ->withoutHeader('Pragma')
            ->withoutHeader('Expires')
            ->withoutHeader('Cache-Control');
    }
}
