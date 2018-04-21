<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 19/04/2018
 * Time: 18:23.
 */

namespace Sufel\App\Controllers;

use Sufel\App\Models\ApiResult;

/**
 * Interface DocumentApiInterface.
 */
interface DocumentApiInterface
{
    /**
     * Get asset document by id.
     *
     * @param int|string $id
     * @param string     $type info, xml, pdf
     *
     * @return ApiResult
     */
    public function getDocument($id, $type);
}
