<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 19/04/2018
 * Time: 18:30.
 */

namespace Sufel\App\ViewModels;

/**
 * Class DocumentLogin.
 */
class DocumentLogin
{
    /**
     * @var string
     */
    private $emisor;
    /**
     * @var string
     */
    private $tipo;
    /**
     * @var string
     */
    private $serie;
    /**
     * @var string
     */
    private $correlativo;
    /**
     * @var \DateTimeInterface
     */
    private $fecha;
    /**
     * @var float
     */
    private $total;

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
     * @return DocumentLogin
     */
    public function setEmisor($emisor)
    {
        $this->emisor = $emisor;

        return $this;
    }

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
     * @return DocumentLogin
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * @return string
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * @param string $serie
     *
     * @return DocumentLogin
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * @return string
     */
    public function getCorrelativo()
    {
        return $this->correlativo;
    }

    /**
     * @param string $correlativo
     *
     * @return DocumentLogin
     */
    public function setCorrelativo($correlativo)
    {
        $this->correlativo = $correlativo;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param \DateTimeInterface $fecha
     *
     * @return DocumentLogin
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param float $total
     *
     * @return DocumentLogin
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }
}
