<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 19/04/2018
 * Time: 17:51.
 */

namespace Sufel\App\Controllers;

use Sufel\App\Models\ApiResult;

/**
 * Interface ClientProfileApiInterface.
 */
interface ClientProfileApiInterface
{
    /**
     * Change client password.
     *
     * @param string $document
     * @param string $old
     * @param string $new
     * @param string $repeat
     *
     * @return ApiResult
     */
    public function changePassword($document, $old, $new, $repeat);
}
