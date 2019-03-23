<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 31/03/2018
 * Time: 22:40.
 */

namespace Sufel\App\Controllers;

use Sufel\App\Models\ApiResult;
use Sufel\App\Models\DocumentConverter;
use Sufel\App\Repository\DocumentRepositoryInterface;
use Sufel\App\Repository\FileReaderInterface;
use Sufel\App\Service\CryptoService;

/**
 * Class ExternalFileApi.
 */
class ExternalFileApi implements ExternalFileApiInterface
{
    use ResponseTrait;

    /**
     * @var CryptoService
     */
    private $crypto;
    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;
    /**
     * @var FileReaderInterface
     */
    private $fileRepository;
    /**
     * @var DocumentConverter
     */
    private $documentConverter;

    /**
     * ExternalFileApi constructor.
     *
     * @param CryptoService               $crypto
     * @param DocumentRepositoryInterface $documentRepository
     * @param FileReaderInterface $fileRepository
     * @param DocumentConverter $documentConverter
     */
    public function __construct(CryptoService $crypto, DocumentRepositoryInterface $documentRepository, FileReaderInterface $fileRepository, DocumentConverter $documentConverter)
    {
        $this->crypto = $crypto;
        $this->documentRepository = $documentRepository;
        $this->fileRepository = $fileRepository;
        $this->documentConverter = $documentConverter;
    }

    /**
     * Download file from hash.
     *
     * @param string $hash
     * @param string $type xml or pdf
     *
     * @return ApiResult
     */
    public function download($hash, $type)
    {
        if (!in_array($type, ['xml', 'pdf'])) {
            return $this->response(404);
        }

        $res = $this->crypto->decrypt($hash);
        if ($res === false) {
            return $this->response(404);
        }
        $obj = json_decode($res);
        $id = $obj->id;
        $doc = $this->documentRepository->get($id);
        if ($doc === null) {
            return $this->response(404);
        }

        $filter = $this->documentConverter->convertToDoc($doc);
        $storageId = $this->documentRepository->getStorageId($filter);

        $result = [
            'file' => $this->fileRepository->read($storageId, $type),
            'type' => $type === 'xml' ? 'text/xml' : 'application/pdf',
        ];

        $headers = [
            'Content-Type' => $result['type'],
            'Content-Disposition' => 'attachment',
            'Content-Length' => strlen($result['file']),
        ];

        return $this->response(200, $result, $headers);
    }
}
