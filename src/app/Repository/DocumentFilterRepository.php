<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 04/04/2018
 * Time: 22:16
 */

namespace Sufel\App\Repository;

use Sufel\App\Repository\Query\FilterBuilderInterface;
use Sufel\App\Repository\Query\QueryJoiner;
use Sufel\App\ViewModels\FilterViewModel;

/**
 * Class DocumentFilterRepository
 * @package Sufel\App\Repository
 */
class DocumentFilterRepository
{
    /**
     * @var DbConnection
     */
    private $db;
    /**
     * @var FilterBuilderInterface[]
     */
    private $builders;
    /**
     * @var QueryJoiner
     */
    private $joiner;

    /**
     * @param FilterBuilderInterface[] $builders
     */
    public function setBuilders($builders)
    {
        $this->builders = $builders;
    }

    /**
     * DocumentFilterRepository constructor.
     * @param DbConnection $db
     * @param QueryJoiner $joiner
     */
    public function __construct(DbConnection $db, QueryJoiner $joiner)
    {
        $this->db = $db;
        $this->joiner = $joiner;
    }

    public function getList(FilterViewModel $filter)
    {
        list($query, $params) = $this->getQueryWithParams($filter);

        $rows = $this->db
            ->fetchAll($query, $params);

        return $rows;
    }

    private function getQueryWithParams(FilterViewModel $filter)
    {
        $parts = [];
        $params = [];
        foreach ($this->builders as $builder) {
            $parts[] = $builder->getQueryPart($filter);
            $params = array_merge($params, $builder->getParams());

            if (!$builder->canContinue()) {
                break;
            }
        }

        $query = 'SELECT id,emisor,tipo,serie,correlativo,fecha,total,cliente_tipo,cliente_doc,cliente_nombre,filename,baja FROM document WHERE ';
        $query.= $this->joiner->joinParts($parts);

        return [$query, $params];
    }
}