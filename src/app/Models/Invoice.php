<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 27/08/2017
 * Time: 19:41
 */

namespace Sufel\App\Models;

/**
 * Class Invoice
 * @package Sufel\App\Models
 */
class Invoice
{
    /**
     * Tipo de Documento (01,03,07,08) -> (FAC, BOL, NCR, NDB).
     *
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
     * Fecha de Emsision.
     *
     * @var string
     */
    private $fecha;

    /**
     * @var float
     */
    private $total;

    /**
     * Ruc del Emisor.
     *
     * @var string
     */
    private $emisor;

    /**
     * Tipo de Documento del Receptor.
     *
     * @var string
     */
    private $clientTipo;

    /**
     * @var string
     */
    private $clientDoc;

    /**
     * @var string
     */
    private $clientName;

    /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param string $tipo
     * @return Invoice
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
     * @return Invoice
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
     * @return Invoice
     */
    public function setCorrelativo($correlativo)
    {
        $this->correlativo = $correlativo;
        return $this;
    }

    /**
     * @return string
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param string $fecha
     * @return Invoice
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
     * @return Invoice
     */
    public function setTotal($total)
    {
        $this->total = $total;
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
     * @return Invoice
     */
    public function setEmisor($emisor)
    {
        $this->emisor = $emisor;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientTipo()
    {
        return $this->clientTipo;
    }

    /**
     * @param string $clientTipo
     * @return Invoice
     */
    public function setClientTipo($clientTipo)
    {
        $this->clientTipo = $clientTipo;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientDoc()
    {
        return $this->clientDoc;
    }

    /**
     * @param string $clientDoc
     * @return Invoice
     */
    public function setClientDoc($clientDoc)
    {
        $this->clientDoc = $clientDoc;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * @param string $clientName
     * @return Invoice
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;
        return $this;
    }
}