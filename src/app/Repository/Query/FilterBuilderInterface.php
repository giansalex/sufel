<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 04/04/2018
 * Time: 21:39.
 */

namespace Sufel\App\Repository\Query;

use Sufel\App\ViewModels\FilterViewModel;

/**
 * Interface FilterBuilderInterface.
 */
interface FilterBuilderInterface
{
    /**
     * @param FilterViewModel $filter
     *
     * @return string
     */
    public function getQueryPart(FilterViewModel $filter);

    /**
     * @return bool
     */
    public function canContinue();
}
