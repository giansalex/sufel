<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 11/04/2018
 * Time: 09:43.
 */

namespace Tests\App\Service;

use Sufel\App\Models\Client;
use Sufel\App\Repository\ClienteRepositoryInterface;
use Sufel\App\Repository\ClientProfileRepositoryInterface;
use Sufel\App\Service\ClientProfile;

class ClientProfileTest extends \PHPUnit_Framework_TestCase
{
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

    private function getClientRepository()
    {
        $stub = $this->getMockBuilder(ClienteRepositoryInterface::class)->getMock();
        $stub->method('get')
            ->willReturn(
                (new Client())
                    ->setPlainPassword('123456')
                    ->setDocument('20123456789')
                    ->setNames('DEMO SAC')
            );

        /** @var $stub ClienteRepositoryInterface */
        return $stub;
    }

    private function getClientProfileRepository()
    {
        $stub = $this->getMockBuilder(ClientProfileRepositoryInterface::class)->getMock();
        $stub->method('updatePassword')
            ->willReturn(true);

        /** @var $stub ClientProfileRepositoryInterface */
        return $stub;
    }
}
