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

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $placeholders[$column] = ":$column";
        }

        return $placeholders;
    }

    /**
     * @param int $value
     *
     * @return array
     *
     * @throws \DomainException when record is not found
     */
    public function find($id)
    {
        $resultSet = $this->findAllBy(array(
            $this->getIdColumn() => $id,
        ));

        if (count($resultSet)) {
            return $resultSet[0];
        }

        throw new \DomainException(
            sprintf(
                '%s record not found by %s %s',
                $this->getTableName(),
                $this->getIdColumn(),
                $id
            )
        );
    }

    /**
     * @param array $conditions
     *
     * @return array
     */
    public function findAllBy(array $conditions = array())
    {
        $sql = sprintf('SELECT * FROM %s', $this->getTableName());
        $whereConditions = $this->assembleEquality($conditions);
        $where = sprintf('WHERE %s', implode(' AND ', $whereConditions));
        $sql .= " $where";

        return $this->selectQuery($sql, $conditions);
    }

    private function assembleEquality(array $conditions)
    {
        $placeholders = $this->columnsToPlaceholders(array_keys($conditions));
        $equalities = array();

        foreach ($placeholders as $column => $placeholder) {
            $equalities[] = "$column = $placeholder";
        }

        return $equalities;
    }

    /**
     * @param int   $id
     * @param array $values
     */
    public function update($id, array $values)
    {
        $conditions = $this->assembleEquality($values);
        $modifications = implode(', ', $conditions);

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s = :id',
            $this->getTableName(),
            $modifications,
            $this->getIdColumn()
        );

        $values[$this->getIdColumn()] = $id;

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($values);
    }
}
