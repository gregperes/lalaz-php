<?php
/**
 * Provides a base class for implements the Singleton Pattern.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
abstract class Singleton extends Object {
	
    private static $instances = array();

    public static function getInstance($className) {
        if (!class_exists($className)) {
            $error = new Error("Error", "Class $classname was not found!");
            $error->render();
        }
        
        $instance = ArrayHelper::getValue($className, self::$instances);

        if (is_null($instance)) {
            self::$instances[$className] = new $className;	
        }

        return self::$instances[$className];
    }
    
    protected abstract function __construct();
}
?>