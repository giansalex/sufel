<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 28/08/2017
 * Time: 19:50.
 */

namespace Sufel\App\Controllers;

use Sufel\App\Models\ApiResult;
use Sufel\App\Repository\ClienteRepositoryInterface;
use Sufel\App\Repository\DocumentFilterRepositoryInterface;
use Sufel\App\Repository\DocumentRepositoryInterface;
use Sufel\App\Repository\FileRepositoryInterface;
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
    private $documentFilterRepository;
    /**
     * @var FileRepositoryInterface
     */
    private $fileRepository;
    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;

    /**
     * ClientApi constructor.
     *
     * @param ClienteRepositoryInterface        $clienteRepository
     * @param DocumentFilterRepositoryInterface $documentFilterRepository
     * @param DocumentRepositoryInterface       $documentRepository
     * @param FileRepositoryInterface           $fileRepository
     */
    public function __construct(
        ClienteRepositoryInterface $clienteRepository,
        DocumentFilterRepositoryInterface $documentFilterRepository,
        DocumentRepositoryInterface $documentRepository,
        FileRepositoryInterface $fileRepository
    ) {
        $this->clienteRepository = $clienteRepository;
        $this->documentFilterRepository = $documentFilterRepository;
        $this->fileRepository = $fileRepository;
        $this->documentRepository = $documentRepository;
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
        $docs = $this->documentFilterRepository->getList($filter);

        return $this->ok($docs);
    }

    /**
     * Get asset document by id.
     *
     * @param string     $document client identiy document
     * @param int|string $id
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

        $fileRepo = $this->fileRepository;

        $result = [];
        if ($type == 'xml') {
            $result['file'] = $fileRepo->getFile($id, 'xml');
            $result['type'] = 'text/xml';
        } else {
            $result['file'] = $fileRepo->getFile($id, 'pdf');
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
