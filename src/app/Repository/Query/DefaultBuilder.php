<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 04/04/2018
 * Time: 21:41.
 */

namespace Sufel\App\Repository\Query;

use Sufel\App\ViewModels\FilterViewModel;

class DefaultBuilder implements FilterBuilderInterface
{
    /**
     * @var QueryJoiner
     */
    private $joiner;

    /**
     * DefaultBuilder constructor.
     *
     * @param QueryJoiner $joiner
     */
    public function __construct(QueryJoiner $joiner)
    {
        $this->joiner = $joiner;
    }

    /**
     * @param FilterViewModel $filter
     *
     * @return string
     */
    public function getQueryPart(FilterViewModel $filter)
    {
        $map = [
            'emisor' => $filter->getEmisor(),
            'cliente_doc' => $filter->getClient(),
        ];
        $query = $this->joiner->joinAll($map);

        $init = $filter->getFecInicio()->format('Y-m-d');
        $end = $filter->getFecFin()->format('Y-m-d');
        $query2 = "fecha BEETWEN '" . $init . "' AND '" . $end . "'";

        return $this->joiner
            ->joinParts([$query, $query2]);
    }

    /**
     * @return bool
     */
    public function canContinue()
    {
        return true;
    }
}
