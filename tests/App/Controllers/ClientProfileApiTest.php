<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 20/04/2018
 * Time: 17:20
 */

namespace Tests\App\Controllers;


use Sufel\App\Controllers\ClientProfileApi;
use Sufel\App\Service\ClientProfile;
use Tests\App\Service\ClientProfileTrait;

class ClientProfileApiTest extends \PHPUnit_Framework_TestCase
{
    use ClientProfileTrait;
    /**
     * @var ClientProfileApi
     */
    private $api;

    protected function setUp()
    {
        $service = new ClientProfile($this->getClientRepository(), $this->getClientProfileRepository());
        $this->api = new ClientProfileApi($service);
    }

    public function testchangePasswordSuccess()
    {
        $result = $this->api->changePassword('20123456789', '123456', '123456', '123456');

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertNotEmpty($result->getData()['message']);
    }

    public function testchangePasswordInvalid()
    {
        $result = $this->api->changePassword('20123456789', '123456', '123456', '123');

        $this->assertEquals(400, $result->getStatusCode());
        $this->assertNotEmpty($result->getData()['message']);
    }
}