<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/08/2017
 * Time: 06:07 PM.
 */

namespace Sufel\App\Repository;

use Sufel\App\Models\Document;
use Sufel\App\ViewModels\DocumentLogin;

/**
 * Class DocumentRepository.
 */
class DocumentRepository implements DocumentRepositoryInterface
{
    /**
     * @var DbConnection
     */
    private $db;

    /**
     * CompanyRepository constructor.
     *
     * @param DbConnection $dbConnection
     */
    public function __construct(DbConnection $dbConnection)
    {
        $this->db = $dbConnection;
    }

    /**
     * Return document's id or FALSE on failure.
     *
     * @param DocumentLogin $info
     *
     * @return int|bool
     */
    public function isAuthorized(DocumentLogin $info)
    {
        $params = [
          $info->getEmisor(),
          $info->getTipo(),
          $info->getSerie(),
          $info->getCorrelativo(),
          $info->getFecha()->format('Y-m-d'),
          $info->getTotal(),
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
     * Return id if document exist.
     *
     * @param Document $document
     *
     * @return int|bool
     */
    public function getId(Document $document)
    {
        $params = [
            $document->getEmisor(),
            $document->getTipo(),
            $document->getSerie(),
            $document->getCorrelativo(),
        ];
        $sql = <<<SQL
SELECT id FROM document WHERE emisor = ? AND tipo = ? AND serie = ? AND correlativo = ?
SQL;

        $con = $this->db->getConnection();
        $stm = $con->prepare($sql);
        $stm->execute($params);
        $id = $stm->fetchColumn();
        $stm = null;

        return $id;
    }

    /**
     * @param Document $document
     * @return string|false
     */
    public function getStorageId(Document $document)
    {
        $params = [
            $document->getEmisor(),
            $document->getTipo(),
            $document->getSerie(),
            $document->getCorrelativo(),
        ];
        $sql = <<<SQL
SELECT storage_id FROM document WHERE emisor = ? AND tipo = ? AND serie = ? AND correlativo = ?
SQL;

        $con = $this->db->getConnection();
        $stm = $con->prepare($sql);
        $stm->execute($params);
        $id = $stm->fetchColumn();
        $stm = null;

        return $id;
    }

    /**
     * Add a new document.
     *
     * @param Document $document
     *
     * @return int|bool
     */
    public function add(Document $document)
    {
        $arguments = [
            $document->getEmisor(),
            $document->getTipo(),
            $document->getSerie(),
            $document->getCorrelativo(),
            $document->getFecha(),
            $document->getTotal(),
            $document->getClientTipo(),
            $document->getClientDoc(),
            $document->getClientName(),
        ];
        $sql = <<<SQL
INSERT INTO document(emisor,tipo,serie,correlativo,fecha,total,cliente_tipo,cliente_doc,cliente_nombre)
VALUES(?,?,?,?,?,?,?,?,?)
SQL;
        $con = $this->db->getConnection();
        $stm = $con->prepare($sql);
        $success = $stm->execute($arguments);
        if (!$success) {
            return false;
        }

        return intval($con->lastInsertId());
    }

    /**
     * @param int $id
     * @param string $storageId
     * @return bool
     */
    public function setStorageId($id, $storageId)
    {
        $params = [$storageId, $id];

        $sql = <<<SQL
UPDATE document SET storage_id = ? WHERE id = ?
SQL;

        return $this->db->exec($sql, $params);
    }

    /**
     * Get Document By id.
     *
     * @param int $id
     *
     * @return array|null
     */
    public function get($id)
    {
        $sql = <<<SQL
SELECT emisor,tipo,serie,correlativo,fecha,total,cliente_tipo,cliente_doc,cliente_nombre,baja 
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
     *
     * @param Document $document
     *
     * @return bool
     */
    public function anular(Document $document)
    {
        $params = [
            $document->getEmisor(),
            $document->getTipo(),
            $document->getSerie(),
            $document->getCorrelativo(),
        ];
        $sql = <<<SQL
UPDATE document SET baja = 1 WHERE emisor = ? AND tipo = ? AND serie = ? AND correlativo = ?
SQL;

        return $this->db->exec($sql, $params);
    }

    /**
     * @param string $ruc
     * @param \DateTime $init
     * @param \DateTime $end
     *
     * @return array
     */
    public function getList($ruc, \DateTime $init, \DateTime $end)
    {
        $params = [
            $ruc,
            $init->format('Y-m-d'),
            $end->format('Y-m-d'),
        ];
        $sql = <<<SQL
SELECT emisor,tipo,serie,correlativo,fecha,total,cliente_tipo,cliente_doc,cliente_nombre,baja FROM document WHERE emisor = ? AND fecha >= ? AND fecha <= ?
SQL;
        $rows = $this->db
            ->fetchAll($sql, $params);

        return $rows;
    }
}
