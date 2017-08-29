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
            'ruc' => '20000000002',
            'password' => '654321',
            'nombre' => 'COMPANY 1'
        ];
        $response = $this->runApp('POST', '/api/company/create?token=jsAkl34Oa2Tyu', $body);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAddDocumentNotAuth()
    {
        $body = ['xml' => '111'];

        $response = $this->runApp('POST', '/api/company/add-document', $body);

        $this->assertEquals(401, $response->getStatusCode());
    }
}
