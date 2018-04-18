<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/11/2017
 * Time: 12:42 PM.
 */

namespace Sufel\App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Sufel\App\Repository\DocumentRepositoryInterface;
use Sufel\App\Repository\FileRepositoryInterface;

/**
 * Class DocumentController.
 */
class DocumentController
{
    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;
    /**
     * @var FileRepositoryInterface
     */
    private $fileRepository;

    /**
     * DocumentController constructor.
     *
     * @param DocumentRepositoryInterface $documentRepository
     * @param FileRepositoryInterface     $fileRepository
     */
    public function __construct(
        DocumentRepositoryInterface $documentRepository,
        FileRepositoryInterface $fileRepository
    ) {
        $this->documentRepository = $documentRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getDocument($request, $response, $args)
    {
        $type = $args['type'];
        if (!in_array($type, ['info', 'xml', 'pdf'])) {
            return $response->withStatus(404);
        }

        $jwt = $request->getAttribute('jwt');
        $id = $jwt->doc;

        $doc = $this->documentRepository->get($id);
        if ($doc === null) {
            return $response->withStatus(404);
        }
        if ($type == 'info') {
            return $response->withJson($doc);
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
            ->withHeader('Content-Disposition', 'attachment')
            ->withHeader('Content-Length', strlen($result['file']))
            ->withoutHeader('Pragma')
            ->withoutHeader('Expires')
            ->withoutHeader('Cache-Control');
    }
}
