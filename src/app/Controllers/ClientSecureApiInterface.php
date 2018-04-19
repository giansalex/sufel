<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 19/04/2018
 * Time: 17:59.
 */

namespace Sufel\App\Controllers;

use Sufel\App\Models\ApiResult;
use Sufel\App\ViewModels\ClientRegister;

/**
 * Interface ClientSecureApiInterface.
 */
interface ClientSecureApiInterface
{
    /**
     * Login client.
     *
     * @param string $document
     * @param string $password
     *
     * @return ApiResult
     */
    public function login($document, $password);

    /**
     * Register or update client.
     *
     * @param ClientRegister $client
     *
     * @return ApiResult
     */
    public function register(ClientRegister $client);
}
