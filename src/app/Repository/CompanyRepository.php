<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/08/2017
 * Time: 05:55 PM
 */

namespace Sufel\App\Repository;
use Sufel\App\Models\Company;

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
     * Verify company.
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

    /**
     * Create new company.
     *
     * @param Company $company
     * @return bool
     */
    public function create(Company $company)
    {
        $params = [
            $company->getRuc(),
            $company->getName(),
            $company->getPassword(),
            $company->isEnable(),
        ];
        $sql = <<<SQL
INSERT INTO company VALUES(?,?,?,?)
SQL;

        return $this->db->exec($sql, $params);
    }

    /**
     * Change password of company.
     *
     * @param string $ruc
     * @param string $new
     * @param string $old
     * @return bool
     */
    public function changePassword($ruc, $new, $old)
    {
        $con = $this->db->getConnection();
        $stm = $con->prepare('SELECT password FROM company WHERE ruc = ?');
        $stm->execute([$ruc]);
        $pass = $stm->fetchColumn();

        if (!password_verify($old, $pass)) {
            return false;
        }
        $cp = (new Company())->setPassword($new);

        return $this->db->exec('UPDATE company SET password = ? WHERE ruc = ?', [$cp->getPassword(), $ruc]);
    }
}