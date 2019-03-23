<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 21/04/2018
 * Time: 12:51
 */

namespace Tests\App\Controllers;

use Sufel\App\Controllers\ClientApi;
use Sufel\App\Models\DocumentConverter;
use Sufel\App\ViewModels\FilterViewModel;

class ClientApiTest extends \PHPUnit_Framework_TestCase
{
    use ClientApiTrait;
    /**
     * @var ClientApi
     */
    private $api;

    protected function setUp()
    {
        $this->api = new ClientApi(
            $this->getClientRepository(),
            $this->getDocumentFilterRepository(),
            $this->getFileReader(),
            $this->getDocumentRepository(),
            new DocumentConverter()
        );
    }

    public function testListCompanies()
    {
        $result = $this->api->getCompanies('20123456789');

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertGreaterThanOrEqual(1, count($result->getData()));
    }

    public function testListDocuments()
    {
        $result = $this->api->getList((new FilterViewModel())
            ->setTipoDoc('01')
            ->setSerie('F001')
            ->setCorrelativo('123'));

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertGreaterThanOrEqual(1, count($result->getData()));
    }

    public function testGetDocumentInfo()
    {
        $result = $this->api->getDocument('20123456789', 1, 'info');

        $this->assertEquals(200, $result->getStatusCode());
        $data = $result->getData();
        $this->assertNotEmpty($data);
        $this->assertNotEmpty($data['emisor']);
        $this->assertNotEmpty($data['tipo']);
        $this->assertNotEmpty($data['serie']);
        $this->assertNotEmpty($data['correlativo']);
    }

    public function testGetDocumentXml()
    {
        $result = $this->api->getDocument('20123456789', 1, 'xml');

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertNotEmpty($result->getData()['file']);
    }

    public function testGetDocumentPdf()
    {
        $result = $this->api->getDocument('20123456789', 1, 'pdf');

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertNotEmpty($result->getData()['file']);
    }
}