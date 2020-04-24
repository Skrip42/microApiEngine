<?php

namespace Engine\Common;

abstract class Singleton
{
    protected static $instance = [];

    protected function __construct()
    {
    }

    public static function getInstance()
    {
        $className = get_called_class();
        if (empty(static::$instance[$className])) {
            static::$instance[$className] = new static();
        }
        return static::$instance[$className];
    }
}
