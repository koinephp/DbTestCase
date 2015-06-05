<?php

namespace KoineTest\TableHelper;

use Koine\TableHelper\ResultSetFilter;
use PHPUnit_Framework_TestCase;

/**
 * KoineTest\TableHelper\ResultSetFilterTest
 */
class ResultSetFilterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function filters()
    {
        $input = array(
            array(
                'foo' => 'foo1',
                'bar' => 'bar1',
                'baz' => 'baz1',
            ),
            array(
                'foo' => 'foo2',
                'bar' => 'bar2',
                'baz' => 'baz2',
            ),
        );

        $expected = array(
            array(
                'foo' => 'foo1',
                'bar' => 'bar1',
            ),
            array(
                'foo' => 'foo2',
                'bar' => 'bar2',
            ),
        );

        $filter = new ResultSetFilter(array('foo', 'bar'));
        $this->assertEquals($expected, $filter->filter($input));
    }

    /**
     * @test
     * @expectedException \DomainException
     * @expectedExceptionMessage At least one column should be set
     */
    public function throwsAnExceptionWhenNoColumnIsGiven()
    {
        new ResultSetFilter(array());
    }
}
