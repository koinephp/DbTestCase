<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

$autoloader = require __DIR__ . '/../vendor/autoload.php';

$config = array(
    'hostname' => 'localhost',
    'database' => 'koine_test',
    'username' => 'root',
    'password' => '',
);

if (file_exists(__DIR__ . '/db_config.php')) {
    $config = require __DIR__ . '/db_config.php';
}

$hostname = $config['hostname'];
$database = $config['database'];
$password = $config['password'];
$username = $config['username'];

$connection = new PDO("mysql:host=$hostname; dbname=$database", $username, $password);
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$connection->exec('SET CHARACTER SET utf8');

$testTable = <<<SQL
    CREATE TABLE `test_table` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
      `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SQL;

try {
    $connection->query("CREATE DATABASE IF NOT EXISTS $database");
    $connection->query('USE ' . $database);
    $connection->query($testTable);
} catch (PDOException $e) {
}

\Koine\PHPUnit\DbTestCase::setConnection($connection);
