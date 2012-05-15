<?php
/**
 * Represents the view of the application.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class View extends Object {
	
    private $data = array();
    private $path = array();
    private $currentDirectory = null;

    public function getData($key) {
        return $this->data[$key];
    }

    public function setData($key, $value) {
        $this->data[$key] = $value;
    }
    
    public function __construct($path = null) {
        if (!is_null($path)) {
            $this->path = $path;
            $prefix = ArrayHelper::getValue("prefix", $path);
            $directory = ArrayHelper::getValue("directory", $path);
            
            $this->currentDirectory = APP_VIEWS_PATH . DIRECTORY_SEPARATOR;
            
            if (!is_null($prefix)) {
                $this->currentDirectory .= $prefix . DIRECTORY_SEPARATOR;
            }
            
            $this->currentDirectory .= $directory . DIRECTORY_SEPARATOR;
        }
    }

    private function validateView($path) {
        if (!file_exists($path)) {
            $filename = StringHelper::extractFileName($path);
            $this->error($this->errorTitle, "The view $filename was not found!");
        }
    }
    
    private function render($filename, $template = null) {
        $this->validateView($filename);

        extract($this->data);
        ob_start();
        
        require_once($filename);
        $output = ob_get_clean();
        
        if (!is_null($template)) {
            if (!file_exists($template)) {
                $templateName = StringHelper::extractFileName($template);
                $this->error($this->errorTitle, "The template $templateName was not found!");
            }
            
            $this->setData("output", $output);
            
            extract($this->data);
            ob_start();
            
            require_once($template);
            $output = ob_get_clean();
        }
        
        if (Config::read("compressHtml")) {
            $output = StringHelper::compress($output);
        }
        
        echo $output;
    }
    
    public function renderAction($template = null) {
        $viewFile = $this->currentDirectory . DIRECTORY_SEPARATOR . "{$this->path["action"]}.view.php";
        $this->render($viewFile, $template);
    }
    
    public function renderView($filename, $data = null, $template = null) {
        if (is_array($data)) {
            $this->data = $data;
        }
        
        if (!is_null($this->currentDirectory) && !empty($this->currentDirectory)) {
            $filename = App::path(array(0 => $filename), $this->currentDirectory);
        }
        
        $this->render($filename, $template);
    }

    public function renderJson($jsonResponse) {
        $json = json_encode($jsonResponse);

        header("Pragma: no-cache");
        header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
        header('Content-Type: text/x-json');
        header("X-JSON: {$json}");
        
        echo $json;
    }
}
?>