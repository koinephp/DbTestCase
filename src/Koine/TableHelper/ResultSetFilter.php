<?php

namespace Koine\TableHelper;

/**
 * Koine\TableHelper\ResultSetFilter
 */
class ResultSetFilter
{
    /**
     * @var array
     */
    private $columns = array();

    /**
     * @param array $columns
     *
     * @throws \DomainException
     */
    public function __construct(array $columns)
    {
        if (!$columns) {
            throw new \DomainException('At least one column should be set');
        }

        $this->columns = $columns;
    }

    /**
     * @param array $resultSet
     *
     * @return array
     */
    public function filter($resultSet)
    {
        return $this->getFilteredRows($resultSet);
    }

    /**
     * @param array $resultSet
     *
     * @return array
     */
    private function getFilteredRows($resultSet)
    {
        $filtered = array();

        foreach ($resultSet as $row) {
            $filtered[] = $this->getFilteredRow($row);
        }

        return $filtered;
    }

    /**
     * @param array $row
     *
     * @return array
     */
    private function getFilteredRow($row)
    {
        $newRow = array();

        foreach ($row as $column => $value) {
            if (in_array($column, $this->columns)) {
                $newRow[$column] = $value;
            }
        }

        return $newRow;
    }
}
