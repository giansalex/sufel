<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 4/04/2018
 * Time: 15:50
 */

namespace Sufel\App\Service;
use Peru\Sunat\UserValidator;

/**
 * Class UserValidatorAdapter
 */
class UserValidatorAdapter implements UserValidateInterface
{

    /**
     * @var UserValidator
     */
    private $service;

    /**
     * UserValidatorAdapter constructor.
     * @param UserValidator $service
     */
    public function __construct(UserValidator $service)
    {
        $this->service = $service;
    }

    /**
     * @param string $document
     * @param string $user
     * @return bool
     */
    function isValid($document, $user)
    {
        $user = strtoupper($user);

        return $this->service->valid($document, $user);
    }
}