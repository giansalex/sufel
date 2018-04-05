<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 04/04/2018
 * Time: 21:41
 */

namespace Sufel\App\Repository\Query;

use Sufel\App\ViewModels\FilterViewModel;

/**
 * Class DocumentBuilder
 * @package Sufel\App\Repository\Query
 */
class DocumentBuilder implements FilterBuilderInterface
{
    /**
     * @var FilterViewModel
     */
    private $filter;

    /**
     * @var QueryJoiner
     */
    private $joiner;

    /**
     * DocumentBuilder constructor.
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
        $this->filter = $filter;

        $map = [
            'tipo' => $filter->getTipoDoc(),
            'serie' => $filter->getSerie(),
            'correlatvio' => $filter->getCorrelativo(),
        ];

        return $this->joiner->joinAll($map);
    }

    /**
     * @return bool
     */
    public function canContinue()
    {
        return $this->hasEmpty([
            $this->filter->getTipoDoc(),
            $this->filter->getSerie(),
            $this->filter->getCorrelativo(),
        ]);
    }

    private function hasEmpty(array $items)
    {
        foreach ($items as $item) {
            if (empty($item)) {
                return true;
            }
        }

        return false;
    }
}