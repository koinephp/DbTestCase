<?php

namespace KoineTest\PHPUnit;

use Koine\PHPUnit\DbTestCase;
use PHPUnit_Framework_TestCase;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class DbTestCaseTest extends DbTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function extendsPhpUnitTestCase()
    {
        $this->assertInstanceOf('PHPUnit_Framework_TestCase', $this);
    }

    /**
     * @test
     */
    public function canAssertTableCount()
    {
        $this->assertTableCount(0, 'test_table');
        $this->insertQuery("INSERT INTO test_table values (0, 'foo', 'bar')");
        $this->assertTableCount(1, 'test_table');
    }
}
