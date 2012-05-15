<?php
/**
 * Abstraction for Log.
 * Defines a base class for log devices.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
abstract class LogDevice extends Object {
    
    public abstract function writeLog($message);
}
?>