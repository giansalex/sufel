<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 19/04/2018
 * Time: 18:28.
 */

namespace Sufel\App\Controllers;

use Sufel\App\Models\ApiResult;
use Sufel\App\ViewModels\DocumentLogin;

interface SecureApiInterface
{
    /**
     * Login by document.
     *
     * @param DocumentLogin $login
     *
     * @return ApiResult
     */
    public function client(DocumentLogin $login);

    /**
     * Login by company.
     *
     * @param string $ruc
     * @param string $password
     *
     * @return ApiResult
     */
    public function company($ruc, $password);
}
