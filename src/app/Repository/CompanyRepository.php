<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/08/2017
 * Time: 05:55 PM
 */

namespace Sufel\App\Repository;
use Psr\Container\ContainerInterface;
use Sufel\App\Models\Company;
use Sufel\App\Utils\PdoErrorLogger;

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
     * @var ContainerInterface
     */
    private $container;

    /**
     * CompanyRepository constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->db = $container->get(DbConnection::class);
        $this->container = $container;
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

        if ($stm->errorCode() !== '00000') {
           $this->writeError($stm);
           return FALSE;
        }

        $obj = $stm->fetchObject();
        if ($obj === FALSE) {
            return FALSE;
        }

        return password_verify($password, $obj->password) && $obj->enable;
    }

    /**
     * Exist company.
     *
     * @param string $ruc
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
            return FALSE;
        }

        if (!password_verify($old, $pass)) {
            return false;
        }
        $cp = (new Company())->setPassword($new);

        return $this->db->exec('UPDATE company SET password = ? WHERE ruc = ?', [$cp->getPassword(), $ruc]);
    }

    private function writeError(\PDOStatement $statement)
    {
        $this->container
            ->get(PdoErrorLogger::class)
            ->err($statement);
    }
}