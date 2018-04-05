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
        $query = $this->getQuery($filter);

        $rows = $this->db
            ->fetchAll($query);

        return $rows;
    }

    private function getQuery(FilterViewModel $filter)
    {
        $parts = [];
        foreach ($this->builders as $builder) {
            $parts[] = $builder->getQueryPart($filter);

            if (!$builder->canContinue()) {
                break;
            }
        }

        $body = 'SELECT emisor,tipo,serie,correlativo,fecha,total,cliente_tipo,cliente_doc,cliente_nombre,filename,baja FROM document WHERE ';

        return $body . $this->joiner->joinParts($parts);
    }
}