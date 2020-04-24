<?php

namespace Services;

class PDOConnection
{
    private static $connections = [];

    public static function getConnection($dbname)
    {
        if (empty(self::$connections[$dbname])) {
            $config = \Services\ConfigReader::getInstance();
            $config = $config['db'][$dbname];
            self::$connections[$dbname] = new \PDO(
                $config['db'],
                $config['user'],
                $config['password'],
                [\PDO::ATTR_PERSISTENT => $config['permanent']]
            );
        }
        return self::$connections[$dbname];
    }
}
