<?php
/**
 * Represents the main class of the framework.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class App extends Singleton {
    
    protected function __construct() {}
    
    public static function path($dirs = array(), $root = null) {
        if (is_null($root)) {
            $root = FRAMEWORK_PATH;
        }
        
        $root = $root . DIRECTORY_SEPARATOR;
        $dirs = array_merge(array($root), $dirs);
        $result = DirectoryHelper::combineDirs($dirs);
        return $result;
    }
    
    public static function run() {
        // Get the config files.
        $configFiles = DirectoryHelper::getFiles(APP_CONFIG_PATH);
        
        // Include the config files.
        foreach ($configFiles as $file) {
            require_once($file["fullName"]);
        }
        
        // Attach the log devices
        Logger::attachDevice(new FileLog());
        
        // Dispach the request to the Controller.
        // The Controller is responsible for all requests from the application.
        $controller = Controller::getInstance("Controller");
        $controller->execute();
    }
}
?>