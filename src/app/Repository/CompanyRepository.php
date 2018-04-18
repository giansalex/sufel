<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/08/2017
 * Time: 05:55 PM.
 */

namespace Sufel\App\Repository;

use Sufel\App\Models\Company;

/**
 * Class CompanyRepository.
 */
class CompanyRepository implements CompanyRepositoryInterface
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
     * Verify company.
     *
     * @param string $ruc
     * @param string $password
     *
     * @return bool
     */
    public function isAuthorized($ruc, $password)
    {
        $con = $this->db->getConnection();
        $stm = $con->prepare('SELECT password, enable FROM company WHERE ruc = ? LIMIT 1');
        $stm->execute([$ruc]);

        if ($stm->errorCode() !== '00000') {
            $this->writeError($stm);

            return false;
        }

        $obj = $stm->fetchObject();
        if ($obj === false) {
            return false;
        }

        return password_verify($password, $obj->password) && $obj->enable;
    }

    /**
     * Exist company.
     *
     * @param string $ruc
     *
     * @return bool
     */
    public function exist($ruc)
    {
        $con = $this->db->getConnection();
        $stm = $con->prepare('SELECT COUNT(1) FROM company WHERE ruc = ?');
        $stm->execute([$ruc]);

        $count = intval($stm->fetchColumn());

        return $count > 0;
    }

    /**
     * Create new company.
     *
     * @param Company $company
     *
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
        $res = $this->db->exec($sql, $params);

        return $res;
    }

    /**
     * Change password of company.
     *
     * @param string $ruc
     * @param string $new
     * @param string $old
     *
     * @return bool
     */
    public function changePassword($ruc, $new, $old)
    {
        $con = $this->db->getConnection();
        $stm = $con->prepare('SELECT password FROM company WHERE ruc = ?');
        $stm->execute([$ruc]);
        $pass = $stm->fetchColumn();

        if ($stm->errorCode() !== '00000') {
            $this->writeError($stm);

            return false;
        }

        if (!password_verify($old, $pass)) {
            return false;
        }
        $cp = (new Company())->setPassword($new);

        return $this->db->exec('UPDATE company SET password = ? WHERE ruc = ?', [$cp->getPassword(), $ruc]);
    }

    private function writeError(\PDOStatement $statement)
    {
        $this->db->log($statement);
    }
}
