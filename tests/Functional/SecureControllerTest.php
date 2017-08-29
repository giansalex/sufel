<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 27/08/2017
 * Time: 15:18
 */

namespace Tests\Functional;

/**
 * Class SecureControllerTest
 * @package Tests\Functional
 */
class SecureControllerTest extends BaseTestCase
{
    public function testCompanyInvalidAuth()
    {
        $data = ['ruc' => '20000000001'];
        $response = $this->runApp('POST', '/api/client/auth', $data);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testCompanyAuth()
    {
        $data = ['ruc' => '20000000001', 'password' => '123456'];
        $response = $this->runApp('POST', '/api/company/auth', $data);

        $this->assertEquals(200, $response->getStatusCode());
        $jwt = $this->getObject($response->getBody());

        $this->assertNotNull($jwt);
        $this->assertNotEmpty($jwt->token);
        $this->assertTrue($jwt->exp > time());

        return $jwt->token;
    }

    public function testClientAuth()
    {
        $data = ['emisor' => '20000000001'];
        $response = $this->runApp('POST', '/api/client/auth', $data);

        $this->assertEquals(400, $response->getStatusCode());
/*        $jwt = $this->getObject($response->getBody());

        $this->assertNotNull($jwt);
        $this->assertNotEmpty($jwt->token);
        $this->assertTrue($jwt->exp > time());

        return $jwt->token;*/
    }
}
