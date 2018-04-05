<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 04/04/2018
 * Time: 22:37
 */

namespace Tests\App\Repository\Query;


use Sufel\App\Repository\Query\DefaultBuilder;
use Sufel\App\Repository\Query\QueryJoiner;
use Sufel\App\ViewModels\FilterViewModel;

class DefaultBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DefaultBuilder
     */
    private $builder;

    protected function setUp()
    {
        $this->builder = new DefaultBuilder(new QueryJoiner());
    }

    public function testFullArguments()
    {
        $model = new FilterViewModel();
        $model
            ->setEmisor('20123456789')
            ->setClient('20000000001')
            ->setFecInicio(new \DateTime())
            ->setFecFin(new \DateTime());

        $query = $this->builder->getQueryPart($model);

        $this->assertNotEmpty($query);
        $this->assertTrue($this->builder->canContinue());
    }

    public function testOnlyDates()
    {
        $model = new FilterViewModel();
        $model
            ->setFecInicio(new \DateTime())
            ->setFecFin(new \DateTime());

        $query = $this->builder->getQueryPart($model);

        $this->assertNotEmpty($query);
        $this->assertTrue($this->builder->canContinue());
    }
}