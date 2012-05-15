<?php
/**
 * Controls the SESSIONS of the application.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class Session extends Singleton {
    
    protected function __construct() {}
    
    public static function isStarted() {
        return isset($_SESSION);
    }
    
    public static function start() {
        if (!self::isStarted()) {
            return session_start();
        }
    }
    
    public static function read($name) {
        self::start();
        return $_SESSION[$name];
    }
    
    public static function write($name, $value) {
        self::start();
        $_SESSION[$name] = $value;
    }
    
    public static function delete($name) {
        self::start();
        unset($_SESSION[$name]);
        return true;
    }
}
?>