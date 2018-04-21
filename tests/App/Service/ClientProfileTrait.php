<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 20/04/2018
 * Time: 17:21
 */

namespace Tests\App\Service;


use Sufel\App\Models\Client;
use Sufel\App\Repository\ClienteRepositoryInterface;
use Sufel\App\Repository\ClientProfileRepositoryInterface;

/**
 * Class ClientProfileTrait
 * @method \PHPUnit_Framework_MockObject_MockBuilder getMockBuilder($class)
 */
trait ClientProfileTrait
{
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