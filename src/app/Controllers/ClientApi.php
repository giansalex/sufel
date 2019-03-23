<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 28/08/2017
 * Time: 19:50.
 */

namespace Sufel\App\Controllers;

use Sufel\App\Models\ApiResult;
use Sufel\App\Models\DocumentConverter;
use Sufel\App\Repository\ClienteRepositoryInterface;
use Sufel\App\Repository\DocumentFilterRepositoryInterface;
use Sufel\App\Repository\DocumentRepositoryInterface;
use Sufel\App\Repository\FileReaderInterface;
use Sufel\App\ViewModels\FilterViewModel;

/**
 * Class ClientApi.
 */
class ClientApi implements ClientApiInterface
{
    use ResponseTrait;
    /**
     * @var ClienteRepositoryInterface
     */
    private $clienteRepository;
    /**
     * @var DocumentFilterRepositoryInterface
     */
    private $documentFilter;
    /**
     * @var FileReaderInterface
     */
    private $fileRepository;
    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;
    /**
     * @var DocumentConverter
     */
    private $documentConverter;

    /**
     * ClientApi constructor.
     *
     * @param ClienteRepositoryInterface        $clienteRepository
     * @param DocumentFilterRepositoryInterface $documentFilter
     * @param FileReaderInterface $fileRepository
     * @param DocumentRepositoryInterface $documentRepository
     * @param DocumentConverter $documentConverter
     */
    public function __construct(ClienteRepositoryInterface $clienteRepository, DocumentFilterRepositoryInterface $documentFilter, FileReaderInterface $fileRepository, DocumentRepositoryInterface $documentRepository, DocumentConverter $documentConverter)
    {
        $this->clienteRepository = $clienteRepository;
        $this->documentFilter = $documentFilter;
        $this->fileRepository = $fileRepository;
        $this->documentRepository = $documentRepository;
        $this->documentConverter = $documentConverter;
    }

    /**
     * Get companies by client.
     *
     * @param string $document Identity document
     *
     * @return ApiResult
     */
    public function getCompanies($document)
    {
        $docs = $this->clienteRepository->getCompanies($document);

        return $this->ok($docs);
    }

    /**
     * Get list document by filter.
     *
     * @param FilterViewModel $filter
     *
     * @return ApiResult
     */
    public function getList(FilterViewModel $filter)
    {
        $docs = $this->documentFilter->getList($filter);

        return $this->ok($docs);
    }

    /**
     * Get asset document by id.
     *
     * @param string $document client identity document
     * @param int $id
     * @param string     $type     info, xml, pdf
     *
     * @return ApiResult
     */
    public function getDocument($document, $id, $type)
    {
        if (!in_array($type, ['info', 'xml', 'pdf'])) {
            return $this->response(404);
        }

        $doc = $this->documentRepository->get($id);
        if ($doc === null) {
            return $this->response(404);
        }
        if ($doc['cliente_doc'] !== $document) {
            return $this->response(401);
        }
        if ($type == 'info') {
            return $this->ok($doc);
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
