<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 28/08/2017
 * Time: 05:51 PM
 */

namespace Sufel\App\Models;

/**
 * Class Document
 * @package Sufel\App\Models
 */
class Document
{
    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * @var string
     */
    private $xml;

    /**
     * @var string
     */
    private $pdf;

    /**
     * @var \DateTime
     */
    private $last;

    /**
     * @return Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param Invoice $invoice
     * @return Document
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;
        return $this;
    }

    /**
     * @return string
     */
    public function getXml()
    {
        return $this->xml;
    }

    /**
     * @param string $xml
     * @return Document
     */
    public function setXml($xml)
    {
        $this->xml = $xml;
        return $this;
    }

    /**
     * @return string
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * @param string $pdf
     * @return Document
     */
    public function setPdf($pdf)
    {
        $this->pdf = $pdf;
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
     * @return Document
     */
    public function setLast($last)
    {
        $this->last = $last;
        return $this;
    }
}