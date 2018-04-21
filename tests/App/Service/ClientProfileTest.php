<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 11/04/2018
 * Time: 09:43.
 */

namespace Tests\App\Service;

use Sufel\App\Service\ClientProfile;

class ClientProfileTest extends \PHPUnit_Framework_TestCase
{
    use ClientProfileTrait;

    /**
     * @var ClientProfile
     */
    private $service;

    protected function setUp()
    {
        $this->service = new ClientProfile($this->getClientRepository(), $this->getClientProfileRepository());
    }

    public function testPasswordNotMatch()
    {
        list($result, $message) = $this->service->changePassword('20484433359', '12345', 'abc', 'jklm');

        $this->assertFalse($result);
        $this->assertEquals('Las contraseñas no coinciden', $message);
    }

    public function testPasswordInvalid()
    {
        list($result, $message) = $this->service->changePassword('20484433359', '12345678', 'abc123', 'abc123');

        $this->assertFalse($result);
        $this->assertEquals('La contraseña original no es la correcta', $message);
    }

    public function testChangePassword()
    {
        list($result, $message) = $this->service->changePassword('20484433359', '123456', '123456', '123456');

        $this->assertTrue($result);
        $this->assertEmpty($message);
    }
}
