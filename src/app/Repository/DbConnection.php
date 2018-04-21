<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/08/2017
 * Time: 05:57 PM.
 */

namespace Sufel\App\Repository;

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
    private $options;

    /**
     * @var PdoErrorLogger
     */
    private $logger;

    /**
     * DbConnection constructor.
     *
     * @param array          $options
     * @param PdoErrorLogger $logger
     */
    public function __construct(array $options, PdoErrorLogger $logger)
    {
        $this->options = $options;
        $this->logger = $logger;
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
                $this->con = new \PDO($this->options['dsn'], $this->options['user'], $this->options['password']);
            } catch (\PDOException $e) {
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
            $this->log($stm);
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
            $this->log($stm);
            $state = false;
        }
        $stm = null;

        return $state;
    }

    /**
     * Log error on statement.
     *
     * @param \PDOStatement $statement
     */
    public function log(\PDOStatement $statement)
    {
        $this->logger->err($statement);
    }
}
