<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 27/08/2017
 * Time: 15:18
 */

namespace Tests\Functional;


class SecureControllerTest extends BaseTestCase
{
    public function testClientAuth()
    {
        $data = ['user' => 'juan'];
        $response = $this->runApp('POST', '/api/client/auth', $data);

/*        $this->assertEquals(400, $response->getStatusCode());
        $jwt = json_decode((string)$response->getBody());

        $this->assertNotNull($jwt);
        $this->assertNotEmpty($jwt->token);
        $this->assertTrue($jwt->exp > time());

        return $jwt->token;*/
    }
}
