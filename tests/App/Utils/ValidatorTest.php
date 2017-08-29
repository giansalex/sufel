<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 28/08/2017
 * Time: 20:56
 */

namespace Tests\App\Utils;

use Sufel\App\Utils\Validator;

/**
 * Class ValidatorTest
 * @package Tests\App\Utils
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testExistParams()
    {
        $arr = ['id' => '21'];

        $this->assertTrue(Validator::existFields($arr, ['id']));
    }

    public function testNotExistParams()
    {
        $arr = ['id' => '21'];

        $this->assertFalse(Validator::existFields($arr, ['r']));
    }
}
