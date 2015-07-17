<?php
/**
 * Class Singleton
 */
abstract class Singleton extends Observable {

    private static $map = array();

    protected function __construct()
    {
        throw new Exception('Singleton class: getInstance()');
    }

    public static function getInstance()
    {
        $class = get_called_class();
        if (!isset(self::$map[$class])) {
            self::$map[$class] = new $class();
        }
        return self::$map[$class];
    }

    public static function getClass($class)
    {
        if (!isset(self::$map[$class])) {
            self::$map[$class] = new $class();
        }
        return self::$map[$class];
    }

}
