<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 29/08/2017
 * Time: 01:03 PM
 */

namespace Tests\Functional;

/**
 * Class CompanyControllerTest
 * @package Tests\Functional
 */
class CompanyControllerTest extends BaseTestCase
{
    public function setUp()
    {
        $data = ['ruc' => '20000000001', 'password' => '123456'];
        $response = $this->runApp('POST', '/api/company/auth', $data);

        $jwt = $this->getObject($response->getBody());

        self::$token = $jwt->token;
    }

    public function testWithoutTokenCreateCompany()
    {
        $response = $this->runApp('POST', '/api/company/create');

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testInvalidTokenCreateCompany()
    {
        $response = $this->runApp('POST', '/api/company/create?token=xyz');

        $this->assertEquals(401, $response->getStatusCode());
        $obj = $this->getObject($response->getBody());
        $this->assertEquals('invalid token', $obj->message);
    }

    public function testInvalidParametersCreateCompany()
    {
        $body = [
            'ruc' => '20000000002',
        ];
        $response = $this->runApp('POST', '/api/company/create?token=jsAkl34Oa2Tyu', $body);

        $this->assertEquals(400, $response->getStatusCode());
        $obj = $this->getObject($response->getBody());
        $this->assertEquals('parametros incompletos', $obj->message);
    }

    public function testCreateCompany()
    {
        $body = [
            'ruc' => '20600055519',
            'password' => '123456',
            'nombre' => 'COMPANY 1'
        ];
        $response = $this->runApp('POST', '/api/company/create?token=jsAkl34Oa2Tyu', $body);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAddDocumentNotValid()
    {
        $body = ['xml' => '111'];

        $response = $this->runApp('POST', '/api/company/add-document', $body);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testAddDocument()
    {
        $xml = file_get_contents(__DIR__ . '/../Resources/20000000001-01-F001-00000001.xml');
        $pdf = file_get_contents(__DIR__ . '/../Resources/impreso.pdf');
        $body = [
            'xml' => base64_encode($xml),
            'pdf' => base64_encode($pdf),
        ];

        $response = $this->runApp('POST', '/api/company/add-document', $body);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
