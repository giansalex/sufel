<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/08/2017
 * Time: 06:07 PM
 */

namespace Sufel\App\Repository;
use Sufel\App\Models\Document;
use Sufel\App\Models\Invoice;

/**
 * Class DocumentRepository
 * @package Sufel\App\Repository
 */
class DocumentRepository
{
    /**
     * @var DbConnection
     */
    private $db;

    /**
     * CompanyRepository constructor.
     * @param DbConnection $dbConnection
     */
    public function __construct(DbConnection $dbConnection)
    {
        $this->db = $dbConnection;
    }

    /**
     * Return document's id or FALSE on failure.
     *
     * @param array $info
     * @return bool|int
     */
    public function isAuthorized($info)
    {
        $params = [
          $info['emisor'],
          $info['tipo'],
          $info['serie'],
          $info['correlativo'],
          (new \DateTime($info['fecha']))->format('Y-m-d'),
          $info['total'],
        ];
        $sql = <<<SQL
SELECT id FROM document WHERE emisor = ? AND tipo = ? AND serie = ? AND correlativo = ? AND fecha = ? AND total = ?
SQL;
        $con = $this->db->getConnection();
        $stm = $con->prepare($sql);
        $stm->execute($params);
        $id = $stm->fetchColumn();
        $stm = null;

        // FALSE if not found.
        return $id;
    }

    /**
     * Return true if document exist.
     *
     * @param Invoice $invoice
     * @return bool
     */
    public function exist(Invoice $invoice)
    {
        $params = [
            $invoice->getEmisor(),
            $invoice->getTipo(),
            $invoice->getSerie(),
            $invoice->getCorrelativo()
        ];
        $sql = <<<SQL
SELECT COUNT(id) FROM document WHERE emisor = ? AND tipo = ? AND serie = ? AND correlativo = ?
SQL;

        $con = $this->db->getConnection();
        $stm = $con->prepare($sql);
        $stm->execute($params);
        $count = $stm->fetchColumn();
        $stm = null;

        return $count > 0;
    }

    /**
     * Add a new document.
     *
     * @param Document $document
     * @return bool
     */
    public function add(Document $document)
    {
        $inv = $document->getInvoice();
        $arguments = [
            $inv->getEmisor(),
            $inv->getTipo(),
            $inv->getSerie(),
            $inv->getCorrelativo(),
            $inv->getFecha(),
            $inv->getTotal(),
            $inv->getClientTipo(),
            $inv->getClientDoc(),
            $inv->getClientName(),
            $document->getFilename(),
        ];
        $sql = <<<SQL
INSERT INTO document(emisor,tipo,serie,correlativo,fecha,total,cliente_tipo,cliente_doc,cliente_nombre,filename)
VALUES(?,?,?,?,?,?,?,?,?,?)
SQL;

        return $this->db->exec($sql, $arguments);
    }

    /**
     * Get Document By id.
     *
     * @param int $id
     * @return array
     */
    public function get($id)
    {
        $sql = <<<SQL
SELECT emisor,tipo,serie,correlativo,fecha,total,cliente_tipo,cliente_doc,cliente_nombre,filename,baja 
FROM document WHERE id = ? LIMIT 1
SQL;
        $rows = $this->db
            ->fetchAll($sql, [$id]);

        if (empty($rows)) {
            return null;
        }

        // Update last access.
        $now = (new \DateTime())
                ->format('Y-m-d H:i:s');
        $this->db->getConnection()->exec("UPDATE document SET `last` = '$now'");

        return $rows[0];
    }

    /**
     * Marca un documento como anulado.
     * @param Invoice $invoice
     * @return bool
     */
    public function anular(Invoice $invoice)
    {
        $params = [
            $invoice->getEmisor(),
            $invoice->getTipo(),
            $invoice->getSerie(),
            $invoice->getCorrelativo(),
        ];
        $sql = <<<SQL
UPDATE document SET baja = 1 WHERE emisor = ? AND tipo = ? AND serie = ? AND correlativo = ?
SQL;
        return $this->db->exec($sql, $params);
    }

    /**
     * Get All documents by company's ruc.
     *
     * @param string $ruc
     * @return array
     */
    public function getByRuc($ruc)
    {
        return $this->db->fetchAll('SELECT * FROM document WHERE emisor = ?', [$ruc]);
    }
}