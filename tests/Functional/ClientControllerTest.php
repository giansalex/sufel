<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 05/04/2018
 * Time: 22:45
 */

namespace Tests\Functional;

class ClientControllerTest extends BaseTestCase
{
    static $ready;

    public function setUp()
    {
        if (self::$ready) {
            return;
        }

        $this->addDocument();

        $data = ['documento' => '20484433359', 'password' => '123456'];
        $response = $this->runApp('POST', '/api/client/login', $data);

        $this->assertEquals(200, $response->getStatusCode());
        $jwt = json_decode((string)$response->getBody());

        self::$token = $jwt->token;
        self::$ready = true;
    }

    public function testListDocsByDate()
    {
        $data = [
            'start' => '2016-03-01',
            'end' => '2016-03-30',
        ];

        $response = $this->runApp('POST', '/api/client/documents', $data);

        $this->assertEquals(200, $response->getStatusCode());
        $docs = json_decode((string)$response->getBody());

        $this->assertTrue(is_array($docs));
        $this->assertGreaterThanOrEqual(1, count($docs));
    }

    public function testFilterByDocument()
    {
        $data = [
            'start' => '2017-06-01',
            'end' => '2017-06-30',
            'tipoDoc' => '07',
            'serie' => 'F001',
            'correlativo' => '211',
        ];

        $response = $this->runApp('POST', '/api/client/documents', $data);

        $this->assertEquals(200, $response->getStatusCode());
        $docs = json_decode((string)$response->getBody());

        $this->assertTrue(is_array($docs));
        $this->assertEquals(1, count($docs));
    }

    public function testGetDocumentInfo()
    {
        $response = $this->runApp('GET', '/api/client/documents/1/resource/info');

        $this->assertEquals(200, $response->getStatusCode());
        $doc = json_decode((string)$response->getBody());

        $this->assertNotEmpty($doc->emisor);
        $this->assertNotEmpty($doc->tipo);
        $this->assertNotEmpty($doc->serie);
        $this->assertNotEmpty($doc->correlativo);
        $this->assertNotEmpty($doc->cliente_tipo);
        $this->assertNotEmpty($doc->cliente_doc);
        $this->assertNotEmpty($doc->cliente_nombre);
        $this->assertNotEmpty(new \DateTime($doc->fecha));
    }

    public function testGetDocumentXml()
    {
        $response = $this->runApp('GET', '/api/client/documents/1/resource/xml');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty((string)$response->getBody());
    }

    public function testGetDocumentPdf()
    {
        $response = $this->runApp('GET', '/api/client/documents/1/resource/pdf');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty((string)$response->getBody());
    }

    public function testGetCompaniesNotAllowed()
    {
        $response = $this->runApp('POST', '/api/client/companies');

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testGetCompanies()
    {
        $response = $this->runApp('GET', '/api/client/companies');

        $this->assertEquals(200, $response->getStatusCode());
        $json = json_decode((string)$response->getBody());

        $this->assertNotEmpty($json);
        $this->assertTrue(is_array($json));
    }

    private function addDocument()
    {
        $this->authCompany();

        $xml = file_get_contents(__DIR__ . '/../Resources/20000000001-07-F001-211.xml');
        $pdf = file_get_contents(__DIR__ . '/../Resources/impreso.pdf');
        $body = [
            'xml' => base64_encode($xml),
            'pdf' => base64_encode($pdf),
        ];

        $response = $this->runApp('POST', '/api/company/documents', $body);
        $this->assertEquals(200, $response->getStatusCode());

        $obj = $this->getObject($response->getBody());
        $this->assertNotEmpty($obj->xml);
        $this->assertNotEmpty($obj->pdf);
    }

    private function authCompany()
    {
        $data = ['ruc' => '20000000001', 'password' => '123456'];
        $response = $this->runApp('POST', '/api/company/auth', $data);

        $jwt = $this->getObject($response->getBody());

        self::$token = $jwt->token;
    }
}