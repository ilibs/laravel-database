<?php
namespace Very\Database\Connectors;

use Illuminate\Database\Connectors\ConnectionFactory as IlluminateConnectionFactory;
use Very\Database\MysqlConnection;

/**
 * Class ConnectionFactory
 * @package Very\Database\Connectors
 */
class ConnectionFactory extends IlluminateConnectionFactory
{
    /**
     * @param string $driver
     * @param \PDO $connection
     * @param string $database
     * @param string $prefix
     * @param array $config
     * @return \Illuminate\Database\Connection
     * @throws \InvalidArgumentException
     */
    protected function createConnection($driver, $connection, $database, $prefix = '', array $config = array())
    {
        if ($this->container->bound($key = "db.connection.{$driver}")) {
            return $this->container->make($key, [$connection, $database, $prefix, $config]);
        }

        if ($driver === 'mysql') {
            return new MysqlConnection($connection, $database, $prefix, $config);
        }
        return parent::createConnection($driver, $connection, $database, $prefix, $config);
    }
}