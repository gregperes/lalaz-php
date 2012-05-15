<?php
/**
 * Represents the configuration of the application.
 * Provides a mechanism for read/writes configuration values.
 * Only one instance of the Config Class exists during the app life cycle.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class Config extends Singleton {

    private $settings = array();

    protected function __construct() {}

    public static function read($key) {
        $self = parent::getInstance("Config");
        $value = ArrayHelper::getValue($key, $self->settings);
        
        if (is_null($value)) {
            $error = new Error("Configuration Error", "The $key config was not found.");
            $error->render();
        }
        
        return $self->settings[$key];
    }

    public static function write($key, $value) {
        $self = parent::getInstance("Config");
        $self->settings[$key] = $value;
    }
}
?>