<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 27/08/2017
 * Time: 19:40
 */

namespace Sufel\App\Utils;

use Sufel\App\Models\Document;

/**
 * Class XmlExtractor
 * @package Sufel\App\Utils
 */
class XmlExtractor
{
    /**
     * Prefix namespace.
     */
    const ROOT_PREFIX = 'xs';

    /**
     * @var \DOMXPath
     */
    private $xpath;

    /**
     * @var string
     */
    private $rootNs;

    /**
     * @param \DOMDocument $doc
     * @return Document
     */
    public function toInvoice(\DOMDocument $doc)
    {
        if (!$doc->documentElement) {
            throw new \InvalidArgumentException('No se pudo cargar el xml');
        }
        $docName = $doc->documentElement->nodeName;

        $this->rootNs = '/'. self::ROOT_PREFIX . ':' . $docName;
        $this->xpath = new \DOMXPath($doc);
        $this->xpath->registerNamespace(self::ROOT_PREFIX, $doc->documentElement->namespaceURI);

        $inv = $this->getInvoice();

        $totalNodeName = 'cac:LegalMonetaryTotal';
        if ($docName == 'CreditNote') {
            $inv->setTipo('07');
        } elseif ($docName == 'DebitNote') {
            $inv->setTipo('08');
            $totalNodeName = 'cac:RequestedMonetaryTotal';
        }
        $inv->setTotal(floatval($this->getFirst($totalNodeName . '/cbc:PayableAmount')));

        return $inv;
    }

    /**
     * @return Document
     */
    private function getInvoice()
    {
        $ubl = $this->getFirst('cbc:UBLVersionID');
        $doc = $this->getFirst('cbc:ID');
        $arr = explode('-', $doc);
        $inv = new Document();
        $inv->setTipo($this->getFirst('cbc:InvoiceTypeCode'))
            ->setSerie($arr[0])
            ->setCorrelativo($arr[1])
            ->setFecha($this->getFirst('cbc:IssueDate'));

        switch ($ubl) {
            case '2.0':
                $inv->setEmisor($this->getFirst('cac:AccountingSupplierParty/cbc:CustomerAssignedAccountID'))
                    ->setClientTipo($this->getFirst('cac:AccountingCustomerParty/cbc:AdditionalAccountID'))
                    ->setClientDoc($this->getFirst('cac:AccountingCustomerParty/cbc:CustomerAssignedAccountID'))
                    ->setClientName($this->getFirst('cac:AccountingCustomerParty/cac:Party/cac:PartyLegalEntity/cbc:RegistrationName'));
                break;
            case '2.1':
                $inv->setEmisor($this->getFirst('cac:AccountingSupplierParty/cac:Party/cac:PartyIdentification/cbc:ID'))
                    ->setClientTipo($this->getFirst('cac:AccountingCustomerParty/cac:Party/cac:PartyIdentification/cbc:ID/@schemeID'))
                    ->setClientDoc($this->getFirst('cac:AccountingCustomerParty/cac:Party/cac:PartyIdentification/cbc:ID'))
                    ->setClientName($this->getFirst('cac:AccountingCustomerParty/cac:Party/cac:PartyLegalEntity/cbc:RegistrationName'));
                break;
            default:
                throw new \Exception("UBL version $ubl no soportada.");
        }

        return $inv;
    }

    /***
     * Obtiene el primer valor del nodo.
     *
     * @param string $query Relativo al root namespace
     * @return null|string
     */
    private function getFirst($query)
    {
        $nodes = $this->xpath->query($this->rootNs . '/' . $query);
        if ($nodes->length > 0) {
            return $nodes->item(0)->nodeValue;
        }

        return null;
    }
}