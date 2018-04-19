<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 19/04/2018
 * Time: 17:04.
 */

namespace Sufel\App\Controllers;

use Sufel\App\Models\ApiResult;
use Sufel\App\ViewModels\FilterViewModel;

/**
 * Interface ClientApiInterface.
 */
interface ClientApiInterface
{
    /**
     * Get companies by client.
     *
     * @param string $document Identity document
     *
     * @return ApiResult
     */
    public function getCompanies($document);

    /**
     * Get list document by filter.
     *
     * @param FilterViewModel $filter
     *
     * @return ApiResult
     */
    public function getList(FilterViewModel $filter);

    /**
     * Get asset document by id.
     *
     * @param string     $document client identiy document
     * @param int|string $id
     * @param string     $type     info, xml, pdf
     *
     * @return ApiResult
     */
    public function getDocument($document, $id, $type);
}
