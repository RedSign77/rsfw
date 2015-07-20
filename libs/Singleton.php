<?php
/**
 * Class Singleton
 */
abstract class Singleton extends Observable {

    private static $map = array();

    protected function __construct()
    {
        parent::__construct();
    }

    public static function getInstance()
    {
        $class = get_called_class();
        if (!isset(self::$map[$class])) {
            self::$map[$class] = new $class();
        }
        return self::$map[$class];
    }

}
