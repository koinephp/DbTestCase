<?php

namespace KoineTests\TableHelper;

use Koine\PHPUnit\DbTestCase;
use Koine\TableHelper\TableHelper;

/**
 * KoineTests\TableHelper\TableHelperTest
 */
class TableHelperTest extends DbTestCase
{
    /** @var TableHelper */
    protected $object;

    public function setUp()
    {
        parent::setUp();
        $this->object = new TableHelper($this->getConnection(), 'test_table');
    }

    /**
     * @test
     */
    public function canGetConnection()
    {
        $this->assertSame($this->getConnection(), $this->object->getConnection());
    }

    /**
     * @test
     */
    public function canGetTableName()
    {
        $this->assertSame('test_table', $this->object->getTableName());
    }

    /**
     * @test
     */
    public function canCountTableName()
    {
        $this->assertEquals(0, $this->object->getNumberOfRows());
        $this->insertQuery("INSERT INTO test_table values (0, 'foo', 'bar')");
        $this->assertEquals(1, $this->object->getNumberOfRows());
    }
}
