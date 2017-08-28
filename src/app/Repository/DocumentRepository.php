<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/08/2017
 * Time: 06:07 PM
 */

namespace Sufel\App\Repository;
use Sufel\App\Models\Document;

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
     * Retorna el identificador del documento o FALSE si no existe.
     *
     * @param array $info
     * @return bool|mixed
     */
    public function isAuthorized($info)
    {
        $con = $this->db->getConnection();
        $stm = $con->prepare('SELECT id FROM document WHERE tipo = ? AND serie = ? AND correlativo = ? AND fecha = ? AND total = ?');
        $stm->execute($info);
        $id = $stm->fetchColumn();

        // FALSE if not found.
        return $id;
    }

    /**
     * Añade un nuevo documento.
     *
     * @param Document $document
     * @return bool
     */
    public function add(Document $document)
    {
        $inv = $document->getInvoice();
        $arguments = [
            'ruc' => $inv->getEmisor(),
        ];
        $con = $this->db->getConnection();
        $stm = $con->prepare('INSERT INTO document VALUES(?,?,?)');
        $res = $stm->execute($arguments);

        return $res;
    }
}