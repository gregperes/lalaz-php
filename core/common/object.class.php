<?php
/**
 * Represents the base class for all objects in the application.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
abstract class Object {
	
    private $className = null;
    protected $errorTitle = "Error";
    
    public function getClassName() {
        if (is_null($this->className)) {
            $this->className = get_class($this);
        }
        
        return $this->className;
    }

    public function log($message) {
        Logger::writeLog($message);
    }

    public function error($title, $message) {
        $error = new Error($title, $message);
        $error->render();
    }

    public function stop($status = null) {
        exit($status);
    }
}
?>