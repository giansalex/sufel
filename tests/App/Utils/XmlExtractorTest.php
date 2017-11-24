<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 27/08/2017
 * Time: 19:50
 */

namespace Tests\App\Utils;

use Sufel\App\Utils\XmlExtractor;

/**
 * Class XmlExtractorTest
 * @package Tests\App\Utils
 */
class XmlExtractorTest extends \PHPUnit_Framework_TestCase
{
    use XmlUtilsTrait;

    public function testGetInvoiceFac()
    {
        $path = __DIR__ . '/../../Resources/20000000001-01-F001-00000001.xml';
        $inv = (new XmlExtractor())->toInvoice($this->loadFromFile($path));

        $this->assertEquals('01', $inv->getTipo());
        $this->assertStringStartsWith('F', $inv->getSerie());
        $this->assertLessThanOrEqual(8, strlen($inv->getCorrelativo()));
        $this->assertTrue((new \DateTime($inv->getFecha()))->getTimestamp() < time());
        $this->assertTrue(is_float($inv->getTotal()));
        $this->assertEquals('6', $inv->getClientTipo());
        $this->assertEquals(11, strlen($inv->getClientDoc()));
        $this->assertEquals(11, strlen($inv->getEmisor()));
    }

    public function testGetInvoiceBol()
    {
        $path = __DIR__ . '/../../Resources/20600995805-03-B001-1.xml';
        $ext = new XmlExtractor();

        $inv = $ext->toInvoice($this->loadFromFile($path));

        $this->assertEquals('03', $inv->getTipo());
        $this->assertStringStartsWith('B', $inv->getSerie());
        $this->assertLessThanOrEqual(8, strlen($inv->getCorrelativo()));
        $this->assertTrue(is_float($inv->getTotal()));
        $this->assertEquals('1', $inv->getClientTipo());
        $this->assertEquals(8, strlen($inv->getClientDoc()));
        $this->assertEquals(11, strlen($inv->getEmisor()));
    }

    public function testGetInvoiceNcr()
    {
        $path = __DIR__ . '/../../Resources/20600995805-07-F001-211.xml';
        $ext = new XmlExtractor();

        $inv = $ext->toInvoice($this->loadFromFile($path));

        $this->assertEquals('07', $inv->getTipo());
        $this->assertLessThanOrEqual(4, strlen($inv->getSerie()));
        $this->assertLessThanOrEqual(8, strlen($inv->getCorrelativo()));
        $this->assertTrue(is_float($inv->getTotal()));
        $this->assertNotEmpty($inv->getClientTipo());
        $this->assertNotEmpty($inv->getClientDoc());
        $this->assertEquals(11, strlen($inv->getEmisor()));
    }

    public function testGetInvoiceNdb()
    {
        $path = __DIR__ . '/../../Resources/20600995805-08-F001-0005.xml';

        $ext = new XmlExtractor();
        $inv = $ext->toInvoice($this->loadFromFile($path));

        $this->assertEquals('08', $inv->getTipo());
        $this->assertLessThanOrEqual(4, strlen($inv->getSerie()));
        $this->assertLessThanOrEqual(8, strlen($inv->getCorrelativo()));
        $this->assertTrue(is_float($inv->getTotal()));
        $this->assertNotEmpty($inv->getClientTipo());
        $this->assertNotEmpty($inv->getClientDoc());
        $this->assertEquals(11, strlen($inv->getEmisor()));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidXml()
    {
        $doc = new \DOMDocument();
        @$doc->loadXML('zdfSFggg');
        $ext = new XmlExtractor();
        $ext->toInvoice($doc);
    }
}
