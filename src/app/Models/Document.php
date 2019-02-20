<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/08/2017
 * Time: 05:51 PM.
 */

namespace Sufel\App\Models;

/**
 * Class Document.
 */
class Document
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
     * @var bool
     */
    private $baja;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var \DateTime
     */
    private $last;

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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     *
     * @return Document
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
     * @return Document
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
     *
     * @return Document
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
     *
     * @return Document
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
     *
     * @return Document
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
     *
     * @return Document
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBaja()
    {
        return $this->baja;
    }

    /**
     * @param bool $baja
     *
     * @return Document
     */
    public function setBaja($baja)
    {
        $this->baja = $baja;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     *
     * @return Document
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLast()
    {
        return $this->last;
    }

    /**
     * @param \DateTime $last
     *
     * @return Document
     */
    public function setLast($last)
    {
        $this->last = $last;

        return $this;
    }
}
