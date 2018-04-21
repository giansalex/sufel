<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 21/04/2018
 * Time: 12:52
 */

namespace Tests\App\Controllers;

use Sufel\App\Repository\ClienteRepositoryInterface;
use Sufel\App\Repository\DocumentFilterRepositoryInterface;
use Sufel\App\Repository\DocumentRepositoryInterface;
use Sufel\App\Repository\FileReaderInterface;

/**
 * Trait ClientApiTrait
 * @method \PHPUnit_Framework_MockObject_MockBuilder getMockBuilder($class)
 */
trait ClientApiTrait
{
    protected function getClientRepository()
    {
        $stub = $this->getMockBuilder(ClienteRepositoryInterface::class)->getMock();

        $stub->method('getCompanies')->willReturn([
            ['ruc' => '20123456780', 'name' => 'Company 1'],
            ['ruc' => '20123456781', 'name' => 'Company 2'],
            ['ruc' => '20123456782', 'name' => 'Company 3'],
        ]);

        /**@var $stub ClienteRepositoryInterface */
        return $stub;
    }

    protected function getDocumentFilterRepository()
    {
        $stub = $this->getMockBuilder(DocumentFilterRepositoryInterface::class)->getMock();

        $stub->method('getList')->willReturn([
            [
                'id' => 1,
                'emisor' => '20123456781',
                'tipo' => '01',
                'serie' => 'F001',
                'correlativo' => '123',
                'fecha' => '2018-04-10',
            ]
        ]);

        /**@var $stub DocumentFilterRepositoryInterface */
        return $stub;
    }

    protected function getDocumentRepository()
    {
        $stub = $this->getMockBuilder(DocumentRepositoryInterface::class)->getMock();

        $stub->method('get')->willReturn([
            'id' => 1,
            'emisor' => '20123456781',
            'tipo' => '01',
            'serie' => 'F001',
            'cliente_doc' => '20123456789',
            'correlativo' => '123',
            'fecha' => '2018-04-10',
        ]);

        /**@var $stub DocumentRepositoryInterface */
        return $stub;
    }

    protected function getFileReader()
    {
        $stub = $this->getMockBuilder(FileReaderInterface::class)->getMock();

        $stub->method('getFile')->willReturn('--any-content-file');

        /**@var $stub FileReaderInterface */
        return $stub;
    }
}