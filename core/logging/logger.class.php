<?php
/**
 * Controls the log devices of the application.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class Logger extends Singleton {
    
    private $devices = array();
    
    protected function __construct() {}
    
    public static function attachDevice($device) {
        $self = parent::getInstance("Logger");
        $self->devices[] = $device;
        return true;
    }
    
    public static function writeLog($message) {
        $self = parent::getInstance("Logger");
        
        foreach ($self->devices as $device) {
            $device->writeLog($message);
        }
        
        return true;
    }
}
?>