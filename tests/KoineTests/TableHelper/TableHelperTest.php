<?php

namespace KoineTests\TableHelper;

use Koine\PHPUnit\DbTestCase;
use Koine\TableHelper\TableHelper;
use Koine\TableHelper\ResultSetFilter;

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
    public function canGetIdColumn()
    {
        $this->assertEquals('id', $this->object->getIdColumn());
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

    /**
     * @test
     */
    public function canInsertRecords()
    {
        $this->object->insert(array(
            'name'  => 'someName',
            'value' => 'someValue',
        ));

        $this->assertTableCount(1, 'test_table');

        $expected = array(
            array(
                'name'  => 'someName',
                'value' => 'someValue',
            ),
        );

        $this->assertEquals($expected, $this->getRecords());
    }

    /**
     * @return array
     */
    private function getRecords($columns = array('name', 'value'))
    {
        $resultSet = $this->selectQuery('SELECT * FROM test_table');

        if ($columns) {
            $filter = new ResultSetFilter($columns);

            return $filter->filter($resultSet);
        }

        return $resultSet;
    }
}
