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
     * @param PDO    $connection
     * @param string $tableName
     */
    public function __construct(PDO $connection, $tableName)
    {
        $this->setConnection($connection);
        $this->setTableName($tableName);
    }

    /**
     * @param PDO $connection
     *
     * @return self
     */
    public function setConnection(PDO $connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param string $tableName
     *
     * @return self
     */
    public function setTableName($tableName)
    {
        $this->tableName = (string) $tableName;

        return $this;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
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
}
