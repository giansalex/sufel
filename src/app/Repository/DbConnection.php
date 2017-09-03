<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/08/2017
 * Time: 05:57 PM
 */

namespace Sufel\App\Repository;

/**
 * Class DbConnection
 * @package Sufel\App\Repository
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
     * Connection constructor.
     * @param array $params
     */
    public function __construct($params)
    {
        $this->dsn = $params['dsn'];
        $this->user = $params['user'];
        $this->password = $params['password'];
    }

    /**
     * Return connection.
     *
     * @return \PDO
     */
    public function getConnection()
    {
        if (!$this->con) {
            $options = [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode=""'];
            $this->con = new \PDO($this->dsn, $this->user, $this->password, $options);
        }

        return $this->con;
    }

    /**
     * Fetch all rows.
     *
     * @param string $query
     * @param array|null $params
     * @param int|null $fetch_style
     * @return array
     */
    public function fetchAll($query, $params = null, $fetch_style = \PDO::FETCH_ASSOC)
    {
        $con = $this->getConnection();
        $stm = $con->prepare($query);
        $stm->execute($params);
        $all = $stm->fetchAll($fetch_style);
        $stm = null;

        return $all;
    }

    /**
     * @param $query
     * @param array|null $params
     * @return bool
     */
    public function exec($query, $params = null)
    {
        $con = $this->getConnection();
        $stm = $con->prepare($query);
        $state = $stm->execute($params);
        $stm = null;

        return $state;
    }
}