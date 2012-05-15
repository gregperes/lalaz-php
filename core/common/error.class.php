<?php
/**
 * Represents the error of the application.
 * This is a utility to render a generic page error when the app returns an error.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class Error extends Object {

    private $data = array();
    
    public function __construct($title, $message) {
        $this->data = array("title" => $title, "message" => $message);
    }
    
    public function render($errorView = null) {
        if (is_null($errorView)) {
            $viewFile = App::path(array("error.view.php"), CORE_VIEWS_PATH);
        } else {
            $viewFile = App::path(array($errorView), APP_VIEWS_PATH);
        }
        
        ob_clean();
        
        $view = new View();
        $view->renderView($viewFile, $this->data);
        $this->stop();
    }
}
?>