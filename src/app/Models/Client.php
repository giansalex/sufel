<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 31/03/2018
 * Time: 05:41 PM.
 */

namespace Sufel\App\Models;

/**
 * Class Client.
 */
class Client
{
    /**
     * @var string
     */
    private $tipo;

    /**
     * @var string
     */
    private $documento;

    /**
     * @var string
     */
    private $password;

    /**
     * @var \DateTimeInterface
     */
    private $created;

    /**
     * Last Access.
     *
     * @var \DateTimeInterface
     */
    private $last;

    /**
     * @var string
     */
    private $emisor;

    /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param string $tipo
     *
     * @return Client
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * @return string
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * @param string $documento
     *
     * @return Client
     */
    public function setDocumento($documento)
    {
        $this->documento = $documento;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return Client
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTimeInterface $created
     *
     * @return Client
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getLast()
    {
        return $this->last;
    }

    /**
     * @param \DateTimeInterface $last
     *
     * @return Client
     */
    public function setLast($last)
    {
        $this->last = $last;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmisor()
    {
        return $this->emisor;
    }

    /**
     * @param string $emisor
     *
     * @return Client
     */
    public function setEmisor($emisor)
    {
        $this->emisor = $emisor;

        return $this;
    }
}
