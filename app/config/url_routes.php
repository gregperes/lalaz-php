<?php
/**
 * This file is responsible for configure the routes of the application.
 * 
 * This file is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */

// Define the prefixes of the application.
// You can add any prefix you want.
UrlRouter::prefix("demo");

// Define the default action of the application.
// Change the root of your application, otherwise the demo app will be displayed.
UrlRouter::root("home", "index", "demo", true);
?>