<?php

namespace PlatBox\API\Service\DataBaseConnectionManager;

use PDO;

/**
 * Class Pool
 * @package PlatBox\API\Service\DataBaseConnectionManager
 */
final class Pool
{
    /**
     * @var array
     */
    private $connectionProperties;

    /**
     * @var array
     */
    private static $instance = [];

    /**
     * Pool constructor.
     * @param array $connectionProperties
     */
    public function __construct(array $connectionProperties)
    {
        $this->connectionProperties = $connectionProperties;
    }

    /**
     * @param string $connection
     * @return PDO
     */
    public function getConnection(string $connection): PDO
    {
        if (!isset(self::$instance[$connection])) {
            self::$instance[$connection] = $this->createConnection($connection);
        }

        return self::$instance[$connection];
    }

    /**
     * @param string $connection
     * @return PDO
     */
    private function createConnection(string $connection): PDO
    {
        $dsn = sprintf(
            'pgsql:host=%s;dbname=%s',
            $this->connectionProperties[$connection]['host'],
            $this->connectionProperties[$connection]['database']
        );

        $pdo =
            new PDO(
                $dsn,
                $this->connectionProperties[$connection]['user'],
                $this->connectionProperties[$connection]['password']
            )
        ;

        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
}
