<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/08/2017
 * Time: 05:57 PM
 */

namespace Sufel\App\Repository;


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
            $this->con = new \PDO($this->dsn, $this->user, $this->password);
        }

        return $this->con;
    }
}