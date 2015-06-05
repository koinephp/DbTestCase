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
     * @test
     */
    public function canFindRecordsBiId()
    {
        $this->object->insert(array(
            'name'  => 'someName',
            'value' => 'someValue',
        ));

        $id = $this->getRecords(array())[0]['id'];

        $expected = array(
            'id'    => $id,
            'name'  => 'someName',
            'value' => 'someValue',
        );

        $record = $this->object->find($id);

        $this->assertEquals($expected, $record);
    }

    /**
     * @test
     */
    public function canFindRecordsByMultipleConditions()
    {
        $this->object->insert(array(
            'name'  => 'foo',
            'value' => 'bar',
        ));
        $this->object->insert(array(
            'name'  => 'foo',
            'value' => 'baz',
        ));
        $this->object->insert(array(
            'name'  => 'baz',
            'value' => 'foo',
        ));

        $records = $this->object->findAllBy(array(
            'name'  => 'foo',
            'value' => 'bar',
        ));

        $this->assertCount(1, $records);
    }

    /**
     * @test
     * @expectedException \DomainException
     * @expectedExceptionMessage test_table record not found by id 0
     */
    public function findThrowsExceptionOnNotFound()
    {
        $this->object->find(0);
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

    /**
     * @test
     */
    public function canUpdateRecord()
    {
        $this->object->insert(array(
            'name'  => 'someName',
            'value' => 'someValue',
        ));

        $id = $this->getRecords(array())[0]['id'];

        $this->object->update($id, array(
            'name'  => 'updated name',
            'value' => 'updated value',
        ));

        $expected = array(
            'id'    => $id,
            'name'  => 'updated name',
            'value' => 'updated value',
        );

        $record = $this->object->find($id);

        $this->assertEquals($expected, $record);
    }
}
