<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/08/2017
 * Time: 05:55 PM
 */

namespace Sufel\App\Repository;

/**
 * Class CompanyRepository
 * @package Sufel\App\Repository
 */
class CompanyRepository
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
     * Verifica que una empresa este autorizada.
     *
     * @param string $ruc
     * @param string $password
     * @return bool
     */
    public function isAuthorized($ruc, $password)
    {
        $con = $this->db->getConnection();
        $stm = $con->prepare('SELECT password, enable FROM company WHERE ruc = ? LIMIT 1');
        $stm->execute([$ruc]);

        $obj = $stm->fetchObject();
        if ($obj === FALSE) {
            return FALSE;
        }

        return password_verify($password, $obj->password) && $obj->enable;
    }
}