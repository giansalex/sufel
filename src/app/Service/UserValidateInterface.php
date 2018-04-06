<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 4/04/2018
 * Time: 15:49
 */

namespace Sufel\App\Service;

/**
 * Interface UserValidateInterface
 */
interface UserValidateInterface
{
    /**
     * @param string $document
     * @param string $user
     * @return bool
     */
    function isValid($document, $user);
}