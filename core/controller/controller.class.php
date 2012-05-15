<?php
/**
 * Represents the controller of the application.
 * The Controller controls identifies the action that will be executed.
 * Only one instance of the Controller Class exists during the app life cycle. 
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class Controller extends Singleton {
    
    protected function __construct() {
        $this->errorTitle = "Controller Error";
    }

    public function execute() {        
        $path = UrlRouter::parse();
        $prefix = $path["prefix"];
        $directory = $path["directory"];
        $action = $path["action"];
        
        $requestData = array_merge_recursive($_POST, $_GET, $_FILES, $path["params"], $path["named"]);
        
        if (!is_null($path["id"])) {
            $requestData["id"] = $path["id"];
        }
        
        $actionPath = $prefix . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . "$action.class.php";
        $actionClassDir = APP_ACTIONS_PATH . DIRECTORY_SEPARATOR . $actionPath;

        if (!file_exists($actionClassDir)) {
            $this->error($this->errorTitle, "Action $action was not found!");
        }

        require_once($actionClassDir);

        $view = new View($path);
        $actionInstance = new $action($requestData);
        $actionInstance->execute($view);
    }
}
?>