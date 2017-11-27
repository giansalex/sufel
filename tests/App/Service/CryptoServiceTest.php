<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 27/11/2017
 * Time: 06:33 PM
 */

namespace Tests\App\Service;

use Sufel\App\Service\CryptoService;

/**
 * Class CryptoServiceTest
 * @package Tests\App\Service
 */
class CryptoServiceTest extends \PHPUnit_Framework_TestCase
{
    const KEY = 'ga3jnnlj3jjAk544';

    private $textPlain = 'my-data';
    /**
     * @var CryptoService
     */
    private $service;

    public function setUp()
    {
        $this->service = new CryptoService(self::KEY);
    }

    public function testEncrypt()
    {
        $encrypt = $this->service->encrypt($this->textPlain);

        $this->assertNotFalse($encrypt);

        return $encrypt;
    }

    /**
     * @depends testEncrypt
     * @param string $text
     */
    public function testDecrypt($text)
    {
        $text = $this->service->decrypt($text);

        $this->assertNotFalse($text);
        $this->assertEquals($this->textPlain, $text);
    }
}