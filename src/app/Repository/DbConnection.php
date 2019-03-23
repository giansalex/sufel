<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/08/2017
 * Time: 05:57 PM.
 */

namespace Sufel\App\Repository;

use Psr\Log\LoggerInterface;
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
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var PdoErrorLogger
     */
    private $pdoLogger;

    /**
     * DbConnection constructor.
     *
     * @param array $options
     * @param LoggerInterface $logger
     * @param PdoErrorLogger $pdoLogger
     */
    public function __construct(array $options, LoggerInterface $logger, PdoErrorLogger $pdoLogger)
    {
        $this->options = $options;
        $this->logger = $logger;
        $this->pdoLogger = $pdoLogger;
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
        if (empty($this->con)) {
            try {
                $this->con = new \PDO($this->options['dsn'], $this->options['user'], $this->options['password']);
            } catch (\PDOException $e) {
                $this->logger->error($e->getMessage());
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
     * @param int $fetch_style
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
        $this->pdoLogger->err($statement);
    }
}
