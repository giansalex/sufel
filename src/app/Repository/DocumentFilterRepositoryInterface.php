<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 18/04/2018
 * Time: 10:07.
 */

namespace Sufel\App\Repository;

use Sufel\App\ViewModels\FilterViewModel;

/**
 * Interface DocumentFilterRepositoryInterface.
 */
interface DocumentFilterRepositoryInterface
{
    /**
     * List documents by filter.
     *
     * @param FilterViewModel $filter
     *
     * @return array
     */
    public function getList(FilterViewModel $filter);
}
