<?php
/**
 * This file is responsible for configure the class paths of the application.
 * The framework will search in this paths when a class is required for the application.
 * 
 * This file is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */

// An example how to add another class path.
Autoloader::addClassPath(APP_MODELS_PATH . DIRECTORY_SEPARATOR . "demo");
?>