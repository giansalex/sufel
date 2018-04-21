<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/11/2017
 * Time: 12:42 PM.
 */

namespace Sufel\App\Controllers;

use Sufel\App\Models\ApiResult;
use Sufel\App\Repository\DocumentRepositoryInterface;
use Sufel\App\Repository\FileReaderInterface;

/**
 * Class DocumentApi.
 */
class DocumentApi implements DocumentApiInterface
{
    use ResponseTrait;

    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;
    /**
     * @var FileReaderInterface
     */
    private $fileRepository;

    /**
     * DocumentApi constructor.
     *
     * @param DocumentRepositoryInterface $documentRepository
     * @param FileReaderInterface $fileRepository
     */
    public function __construct(
        DocumentRepositoryInterface $documentRepository,
        FileReaderInterface $fileRepository
    ) {
        $this->documentRepository = $documentRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * Get asset document by id.
     *
     * @param int|string $id
     * @param string     $type info, xml, pdf
     *
     * @return ApiResult
     */
    public function getDocument($id, $type)
    {
        if (!in_array($type, ['info', 'xml', 'pdf'])) {
            return $this->response(404);
        }

        $doc = $this->documentRepository->get($id);
        if ($doc === null) {
            return $this->response(404);
        }
        if ($type == 'info') {
            return $this->ok($doc);
        }

        $result = [];
        if ($type == 'xml') {
            $result['file'] = $this->fileRepository->getFile($id, 'xml');
            $result['type'] = 'text/xml';
        } else {
            $result['file'] = $this->fileRepository->getFile($id, 'pdf');
            $result['type'] = 'application/pdf';
        }

        $headers = [
            'Content-Type' => $result['type'],
            'Content-Disposition' => 'attachment',
            'Content-Length' => strlen($result['file']),
        ];

        return $this->response(200, $result, $headers);
    }
}
