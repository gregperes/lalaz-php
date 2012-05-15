<?php
/**
 * This is the main of the application, only this file should be included in your code.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */

// Define the base directories.
define("ROOT_PATH", dirname(dirname(__FILE__)));
define("CORE_PATH", ROOT_PATH . DIRECTORY_SEPARATOR . "core");
define("CORE_WEBFILES_PATH", CORE_PATH . DIRECTORY_SEPARATOR . "webfiles");
define("CORE_VIEWS_PATH", CORE_WEBFILES_PATH . DIRECTORY_SEPARATOR . "views");

// Define the directories of the application.
define("APP_PATH", ROOT_PATH . DIRECTORY_SEPARATOR . "app");
define("APP_ACTIONS_PATH", APP_PATH . DIRECTORY_SEPARATOR . "action");
define("APP_MODELS_PATH", APP_PATH . DIRECTORY_SEPARATOR . "model");
define("APP_VIEWS_PATH", APP_PATH . DIRECTORY_SEPARATOR . "view");
define("APP_CONFIG_PATH", APP_PATH . DIRECTORY_SEPARATOR . "config");

// Define and include the autoloader class.
$autoloaderFile = CORE_PATH . DIRECTORY_SEPARATOR . "common" . DIRECTORY_SEPARATOR . "autoloader.class.php";
require_once($autoloaderFile);

// Configure the class paths for autoloader class.
// Add the directories of the framework.
Autoloader::addClassPath(CORE_PATH);
Autoloader::addClassPath(CORE_PATH . DIRECTORY_SEPARATOR . "action");
Autoloader::addClassPath(CORE_PATH . DIRECTORY_SEPARATOR . "common");
Autoloader::addClassPath(CORE_PATH . DIRECTORY_SEPARATOR . "controller");
Autoloader::addClassPath(CORE_PATH . DIRECTORY_SEPARATOR . "data");
Autoloader::addClassPath(CORE_PATH . DIRECTORY_SEPARATOR . "helper");
Autoloader::addClassPath(CORE_PATH . DIRECTORY_SEPARATOR . "logging");
Autoloader::addClassPath(CORE_PATH . DIRECTORY_SEPARATOR . "model");
Autoloader::addClassPath(CORE_PATH . DIRECTORY_SEPARATOR . "pattern");
Autoloader::addClassPath(CORE_PATH . DIRECTORY_SEPARATOR . "routing");
Autoloader::addClassPath(CORE_PATH . DIRECTORY_SEPARATOR . "security");
Autoloader::addClassPath(CORE_PATH . DIRECTORY_SEPARATOR . "view");

// Add the directorires of the application.
Autoloader::addClassPath(APP_ACTIONS_PATH);
Autoloader::addClassPath(APP_MODELS_PATH);

// Register the autoload function.
spl_autoload_register(array("Autoloader", "register"));
?>