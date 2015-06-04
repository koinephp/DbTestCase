<?php

namespace Koine\PHPUnit;

use PDO;
use PHPUnit_Framework_TestCase;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class DbTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @param PDO
     */
    private static $connection;

    public function setUp()
    {
        $this->getConnection()->beginTransaction();
    }

    public function tearDown()
    {
        $this->getConnection()->rollBack();
    }

    /**
     * @param int    $count
     * @param string $tableName
     */
    public function assertTableCount($count, $tableName)
    {
        $actual = $this->getNumberOfTableRows($tableName);
        $count = (int) $count;

        $this->assertEquals(
            $count,
            $actual,
            "Failed asserting that table '$tableName' has $count records. $actual found."
        );
    }

    /**
     * @param string tableName
     *
     * @return int
     */
    public function getNumberOfTableRows($tableName)
    {
        $records = $this->selectQuery("SELECT count(*) as count FROM $tableName");
        return (int) $records[0]['count'];
    }

    /**
     * @param string $sql
     * @param array  $params
     *
     * @return array
     */
    public function selectQuery($sql, array $params = array())
    {
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * @return PDO
     */
    public function getConnection()
    {
        return self::$connection;
    }

    /**
     * @param PDO $pdo
     */
    public static function setConnection(PDO $pdo)
    {
        self::$connection = $pdo;
    }

    /**
     * @param string $sql
     * @param array  $params
     */
    public function insertQuery($sql, array $params = array())
    {
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);
    }
}
