<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 19/04/2018
 * Time: 18:07.
 */

namespace Sufel\App\Controllers;

use Sufel\App\Models\ApiResult;

/**
 * Interface CompanyApiInterface.
 */
interface CompanyApiInterface
{
    /**
     * Create new company.
     *
     * @param string $ruc
     * @param string $nombre
     * @param string $password
     *
     * @return ApiResult
     */
    public function createCompany($ruc, $nombre, $password);

    /**
     * Add new document.
     *
     * @param string $ruc
     * @param string $xml
     * @param string $pdf
     *
     * @return ApiResult
     */
    public function addDocument($ruc, $xml, $pdf);

    /**
     * Change password.
     *
     * @param string $ruc
     * @param string $new
     * @param string $old
     *
     * @return ApiResult
     */
    public function changePassword($ruc, $new, $old);

    /**
     * Down document.
     *
     * @param string $ruc
     * @param string $tipo
     * @param string $serie
     * @param string $correlativo
     *
     * @return ApiResult
     */
    public function anularDocument($ruc, $tipo, $serie, $correlativo);

    /**
     * List document by range date.
     *
     * @param string    $ruc
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return ApiResult
     */
    public function getInvoices($ruc, \DateTime $start, \DateTime $end);
}
