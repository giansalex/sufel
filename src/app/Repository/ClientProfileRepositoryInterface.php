<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 18/04/2018
 * Time: 10:05
 */

namespace Sufel\App\Repository;

/**
 * Interface ClientProfileRepositoryInterface
 */
interface ClientProfileRepositoryInterface
{
    /**
     * @param string $document
     * @param string $password
     *
     * @return bool
     */
    public function updatePassword($document, $password);

    /**
     * Update client last access.
     *
     * @param string $document
     * @return bool
     */
    public function updateAccess($document);
}