<?php
/**
 * Implementation for logging into a text file.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class FileLog extends LogDevice {
    
    private $logDirectory = null;
    
    public function __construct() {
        $this->logDirectory = App::path(array("log"), APP_PATH);
    }
    
    public function writeLog($message) {
        DirectoryHelper::createDir($this->logDirectory);
        
        $fileName = date("mdY") . ".log";
        $path = App::path(array($fileName), $this->logDirectory);
        $message = date("m-d-Y H:i:s") . " - {$message}\n";
        
        $fh = fopen($path, 'a') or $this->error($this->errorTitle, "Can't create the log file.");
        fwrite($fh, $message);
        fclose($fh);
    }
}
?>