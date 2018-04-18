<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/08/2017
 * Time: 05:57 PM.
 */

namespace Sufel\App\Repository;

use Psr\Container\ContainerInterface;
use Sufel\App\Utils\PdoErrorLogger;

/**
 * Class DbConnection.
 */
class DbConnection
{
    /**
     * @var \PDO
     */
    private $con;

    /**
     * @var string
     */
    private $dsn;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * DbConnection constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $params = $container->get('settings')['db'];
        $this->dsn = $params['dsn'];
        $this->user = $params['user'];
        $this->password = $params['password'];

        $this->container = $container;
    }

    /**
     * Return connection.
     *
     * @return \PDO
     *
     * @throws \Exception
     */
    public function getConnection()
    {
        if (!$this->con) {
            try {
                $this->con = new \PDO($this->dsn, $this->user, $this->password);
            } catch (\PDOException $e) {
                $this->container->get('logger')
                    ->error($e->getMessage());
                throw new \Exception('No se pudo conectar');
            }
        }

        return $this->con;
    }

    /**
     * Fetch all rows.
     *
     * @param string     $query
     * @param array|null $params
     * @param int|null   $fetch_style
     *
     * @return array
     */
    public function fetchAll($query, $params = null, $fetch_style = \PDO::FETCH_ASSOC)
    {
        $con = $this->getConnection();
        $stm = $con->prepare($query);
        $stm->execute($params);
        if ($stm->errorCode() !== '00000') {
            $this->writeError($stm);
            $stm = null;

            return [];
        }
        $all = $stm->fetchAll($fetch_style);
        $stm = null;

        return $all;
    }

    /**
     * @param $query
     * @param array|null $params
     *
     * @return bool
     */
    public function exec($query, $params = null)
    {
        $con = $this->getConnection();
        $stm = $con->prepare($query);
        $state = $stm->execute($params);

        if ($stm->errorCode() !== '00000') {
            $this->writeError($stm);
            $state = false;
        }
        $stm = null;

        return $state;
    }

    private function writeError(\PDOStatement $statement)
    {
        $this->container
            ->get(PdoErrorLogger::class)
            ->err($statement);
    }
}
