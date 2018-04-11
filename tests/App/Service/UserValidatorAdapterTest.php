<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 11/04/2018
 * Time: 09:39
 */

namespace Tests\App\Service;

use Peru\Http\ContextClient;
use Peru\Sunat\UserValidator;
use Sufel\App\Service\UserValidatorAdapter;

class UserValidatorAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testValid()
    {
        $service = new UserValidatorAdapter(new UserValidator(new ContextClient()));

        $result = $service->isValid('20000000001', 'ABCDEFGH');

        $this->assertFalse($result);
    }
}