<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 18/04/2018
 * Time: 10:06.
 */

namespace Sufel\App\Repository;

use Sufel\App\Models\Company;

/**
 * Interface CompanyRepositoryInterface.
 */
interface CompanyRepositoryInterface
{
    /**
     * Verify company.
     *
     * @param string $ruc
     * @param string $password
     *
     * @return bool
     */
    public function isAuthorized($ruc, $password);

    /**
     * Exist company.
     *
     * @param string $ruc
     *
     * @return bool
     */
    public function exist($ruc);

    /**
     * Create new company.
     *
     * @param Company $company
     *
     * @return bool
     */
    public function create(Company $company);

    /**
     * Change password of company.
     *
     * @param string $ruc
     * @param string $new
     * @param string $old
     *
     * @return bool
     */
    public function changePassword($ruc, $new, $old);
}
