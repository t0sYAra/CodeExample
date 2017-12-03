<?php
namespace AntonPavlov\PersonalSite\Base;

class Registry
{
    private static $instance = null;
    private $registry = array(); 
 
    private function __construct()
    {
    }

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public static function set($key, $value) {
        self::init()->registry[$key] = $value;
    }
 
    public static function get($key) {
        return self::init()->registry[$key];
    }

    private function __wakeup()
    {
    }

    private function __clone()
    {
    }

}