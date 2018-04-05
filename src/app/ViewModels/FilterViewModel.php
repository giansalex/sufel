<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 04/04/2018
 * Time: 21:19.
 */

namespace Sufel\App\ViewModels;

/**
 * Class FilterViewModel.
 */
class FilterViewModel
{
    /**
     * @var string
     */
    private $client;
    /**
     * @var string
     */
    private $emisor;
    /**
     * @var string
     */
    private $tipoDoc;
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
    private $fecInicio;
    /**
     * @var \DateTimeInterface
     */
    private $fecFin;

    /**
     * @return string
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $client
     * @return FilterViewModel
     */
    public function setClient($client)
    {
        $this->client = $client;
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
     * @return FilterViewModel
     */
    public function setEmisor($emisor)
    {
        $this->emisor = $emisor;
        return $this;
    }

    /**
     * @return string
     */
    public function getTipoDoc()
    {
        return $this->tipoDoc;
    }

    /**
     * @param string $tipoDoc
     * @return FilterViewModel
     */
    public function setTipoDoc($tipoDoc)
    {
        $this->tipoDoc = $tipoDoc;
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
     * @return FilterViewModel
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
     * @return FilterViewModel
     */
    public function setCorrelativo($correlativo)
    {
        $this->correlativo = $correlativo;
        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getFecInicio()
    {
        return $this->fecInicio;
    }

    /**
     * @param \DateTimeInterface $fecInicio
     * @return FilterViewModel
     */
    public function setFecInicio($fecInicio)
    {
        $this->fecInicio = $fecInicio;
        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getFecFin()
    {
        return $this->fecFin;
    }

    /**
     * @param \DateTimeInterface $fecFin
     * @return FilterViewModel
     */
    public function setFecFin($fecFin)
    {
        $this->fecFin = $fecFin;
        return $this;
    }
}
