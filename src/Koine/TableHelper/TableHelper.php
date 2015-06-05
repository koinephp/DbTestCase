<?php

namespace Koine\TableHelper;

use PDO;

/**
 * Koine\TableHelper\TableHelper
 */
class TableHelper
{
    /**
     * @var PDO
     */
    private $connection;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var string
     */
    private $idColumn;

    /**
     * @param PDO    $connection
     * @param string $tableName
     * @param string $idColumn
     */
    public function __construct(PDO $connection, $tableName, $idColumn = 'id')
    {
        $this->connection = $connection;
        $this->tableName = (string) $tableName;
        $this->idColumn = (string) $idColumn;
    }

    /**
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @return string
     */
    public function getIdColumn()
    {
        return $this->idColumn;
    }

    /**
     * @return int
     */
    public function getNumberOfRows()
    {
        $tableName = $this->getTableName();
        $records = $this->selectQuery("SELECT count(*) as count FROM $tableName");

        return (int) $records[0]['count'];
    }

    /**
     * @param string $sql
     * @param array  $params
     *
     * @return array
     */
    private function selectQuery($sql, array $params = array())
    {
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * @param array $values
     */
    public function insert(array $values)
    {
        $sql = 'INSERT INTO %s (%s) VALUES (%s)';
        $columnNames = array_keys($values);
        $columns = implode(', ', $columnNames);
        $placeholders = implode(', ', $this->columnsToPlaceholders($columnNames));
        $sql = sprintf(
            $sql,
            $this->getTableName(),
            $columns,
            $placeholders
        );
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($values);
    }

    /**
     * @param array $columns
     *
     * @return array
     */
    private function columnsToPlaceholders(array $columns)
    {
        $placeholders = array();

        foreach ($columns as $column) {
            $placeholders[] = ":$column";
        }

        return $placeholders;
    }
}