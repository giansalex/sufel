<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 19/04/2018
 * Time: 18:40.
 */

namespace Sufel\App\Controllers;

use Sufel\App\Models\ApiResult;

/**
 * Interface ExternalFileApiInterface.
 */
interface ExternalFileApiInterface
{
    /**
     * Download file from hash.
     *
     * @param string $hash
     * @param string $type xml or pdf
     *
     * @return ApiResult
     */
    public function download($hash, $type);
}
