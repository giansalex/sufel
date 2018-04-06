<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 05/04/2018
 * Time: 21:50
 */

namespace Tests\Functional;

use Slim\App;
use Sufel\App\Service\UserValidateInterface;

class ClientSecureControllerTest extends BaseTestCase
{
    public function testRegisterIncompleteFields()
    {
        $data = ['documento' => '20484433359', 'usuario_sol' => 'abc'];
        $response = $this->runApp('POST', '/api/client/register', $data);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testRegisterSuccess()
    {
        $response = $this->registerUser();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testLoginInvalid()
    {
        $data = ['documento' => '20484433359', 'password' => 'abc'];
        $response = $this->runApp('POST', '/api/client/login', $data);

        $this->assertEquals(400, $response->getStatusCode());
        $obj = json_decode((string)$response->getBody());

        $this->assertContains('inválidas', $obj->message);
    }

    public function testLoginValid()
    {
        $data = ['documento' => '20484433359', 'password' => '123456'];
        $response = $this->runApp('POST', '/api/client/login', $data);

        $this->assertEquals(200, $response->getStatusCode());
        $jwt = json_decode((string)$response->getBody());

        $this->assertNotNull($jwt);
        $this->assertNotEmpty($jwt->token);
        $this->assertTrue($jwt->expire > time());
    }

    /**
     * @param bool $status
     */
    private function setUserValidator($status = true)
    {
        $stub = $this->getMockBuilder(UserValidateInterface::class)
            ->getMock();

        $stub->method('isValid')
            ->willReturn($status);

        $this->onBeforeRun = function (App $app) use ($stub) {
            $app->getContainer()[UserValidateInterface::class] = function () use ($stub) {
                return $stub;
            };
        };
    }

    private function registerUser()
    {
        $data = [
            'documento' => '20484433359',
            'nombres' => 'CLIENT SAC',
            'password' => '123456',
            'repeat_password' => '123456',
            'usuario_sol' => 'abc',
        ];
        $this->setUserValidator();
        $response = $this->runApp('POST', '/api/client/register', $data);

        return $response;
    }
}