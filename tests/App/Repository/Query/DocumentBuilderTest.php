<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 04/04/2018
 * Time: 22:30
 */

namespace Tests\App\Repository\Query;

use Sufel\App\Repository\Query\DocumentBuilder;
use Sufel\App\Repository\Query\QueryJoiner;
use Sufel\App\ViewModels\FilterViewModel;

class DocumentBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DocumentBuilder
     */
    private $builder;

    protected function setUp()
    {
        $this->builder = new DocumentBuilder(new QueryJoiner());
    }

    public function testFullDocument()
    {
        $model = new FilterViewModel();
        $model->setSerie('F001')
            ->setCorrelativo('1')
            ->setTipoDoc('01');

        $query = $this->builder->getQueryPart($model);

        $this->assertNotEmpty($query);
        $this->assertEquals(3, count($this->builder->getParams()));
        $this->assertFalse($this->builder->canContinue());
    }

    public function testNotFullDocument()
    {
        $model = new FilterViewModel();
        $model->setSerie('F001')
            ->setTipoDoc('01');

        $query = $this->builder->getQueryPart($model);

        $this->assertNotEmpty($query);
        $this->assertEquals(2, count($this->builder->getParams()));
        $this->assertTrue($this->builder->canContinue());
    }
}