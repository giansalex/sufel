<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 28/08/2017
 * Time: 19:51.
 */

namespace Sufel\App\Controllers;

use Sufel\App\Models\ApiResult;
use Sufel\App\Models\Company;
use Sufel\App\Models\Document;
use Sufel\App\Models\Invoice;
use Sufel\App\Repository\CompanyRepositoryInterface;
use Sufel\App\Repository\DocumentRepositoryInterface;
use Sufel\App\Service\LinkGenerator;
use Sufel\App\Utils\XmlExtractor;

/**
 * Class CompanyApi.
 */
class CompanyApi implements CompanyApiInterface
{
    use ResponseTrait;
    /**
     * @var CompanyRepositoryInterface
     */
    private $companyRepository;
    /**
     * @var DocumentRepositoryInterface
     */
    private $documentRepository;
    /**
     * @var LinkGenerator
     */
    private $generator;
    /**
     * @var string
     */
    private $uploadDir;

    /**
     * CompanyApi constructor.
     *
     * @param CompanyRepositoryInterface  $companyRepository
     * @param DocumentRepositoryInterface $documentRepository
     * @param LinkGenerator               $generator
     * @param string                      $uploadDir
     */
    public function __construct(
        CompanyRepositoryInterface $companyRepository,
        DocumentRepositoryInterface $documentRepository,
        LinkGenerator $generator,
        $uploadDir
    ) {
        $this->companyRepository = $companyRepository;
        $this->documentRepository = $documentRepository;
        $this->generator = $generator;
        $this->uploadDir = $uploadDir;
    }

    /**
     * Create new company.
     *
     * @param string $ruc
     * @param string $nombre
     * @param string $password
     *
     * @return ApiResult
     */
    public function createCompany($ruc, $nombre, $password)
    {
        $repo = $this->companyRepository;
        if ($repo->exist($ruc)) {
            return $this->response(400, ['message' => 'Esta empresa ya esta registrada']);
        }

        $cp = new Company();
        $cp->setRuc($ruc)
            ->setName($nombre)
            ->setPassword($password)
            ->setEnable(true);

        return $this->response($repo->create($cp) ? 200 : 500);
    }

    /**
     * Add new document.
     *
     * @param string $ruc
     * @param string $xml
     * @param string $pdf
     *
     * @return ApiResult
     */
    public function addDocument($ruc, $xml, $pdf)
    {
        $xml = base64_decode($xml);
        $inv = $this->getInvoice($xml);

        if (empty($inv->getEmisor()) || $ruc != trim($inv->getEmisor())) {
            return $this->response(400, ['message' => 'el ruc del emisor no coincide con el XML']);
        }

        $repo = $this->documentRepository;
        if ($repo->exist($inv)) {
            return $this->response(400, ['message' => 'documento ya existe']);
        }

        $name = join('-', [$inv->getEmisor(), $inv->getTipo(), $inv->getSerie(), $inv->getCorrelativo()]);
        $pdf = base64_decode($pdf);

        $doc = new Document();
        $doc->setInvoice($inv)
            ->setFilename($name);
        $idSave = $repo->add($doc);

        if ($idSave === false) {
            return $this->response(500);
        }

        $rootDir = $this->uploadDir.DIRECTORY_SEPARATOR.$inv->getEmisor();
        if (!is_dir($rootDir)) {
            $oldmask = umask(0);
            mkdir($rootDir, 0777, true);
            umask($oldmask);
        }

        $path = $rootDir.DIRECTORY_SEPARATOR.$name.'.zip';
        $zip = new \ZipArchive();
        $zip->open($path, \ZipArchive::CREATE);
        $zip->addFromString($name.'.xml', $xml);
        $zip->addFromString($name.'.pdf', $pdf);
        $zip->close();

        $links = $this->generator->getLinks(['id' => $idSave, 'ruc' => $inv->getEmisor()]);

        return $this->ok($links);
    }

    /**
     * Change password.
     *
     * @param string $ruc
     * @param string $new
     * @param string $old
     *
     * @return ApiResult
     */
    public function changePassword($ruc, $new, $old)
    {
        $result = $this->companyRepository->changePassword($ruc, $new, $old);
        if (!$result) {
            return $this->response(400, ['message' => 'No se pudo cambiar la contraseña']);
        }

        return $this->ok(['message' => 'contraseña actualizada']);
    }

    /**
     * Down document.
     *
     * @param string $ruc
     * @param string $tipo
     * @param string $serie
     * @param string $correlativo
     *
     * @return ApiResult
     */
    public function anularDocument($ruc, $tipo, $serie, $correlativo)
    {
        $inv = new Invoice();
        $inv->setEmisor($ruc)
            ->setTipo($tipo)
            ->setSerie($serie)
            ->setCorrelativo($correlativo);

        $result = $this->documentRepository->anular($inv);
        if (!$result) {
            return $this->response(400, ['message' => 'No se pudo anular el documento']);
        }

        return $this->ok(['message' => 'comprobante anulado']);
    }

    /**
     * List document by range date.
     *
     * @param string    $ruc
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return ApiResult
     */
    public function getInvoices($ruc, \DateTime $start, \DateTime $end)
    {
        $result = $this->documentRepository->getList($ruc, $start, $end);

        return $this->ok($result);
    }

    /**
     * @param $xml
     *
     * @return \Sufel\App\Models\Invoice
     */
    private function getInvoice($xml)
    {
        $doc = new \DOMDocument();
        @$doc->loadXML($xml);
        $ext = new XmlExtractor();

        return $ext->toInvoice($doc);
    }
}
