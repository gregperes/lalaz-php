<?php
/**
 * This file is responsible for configure the databases for the environmnets of the application.
 * 
 * This file is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */

Config::write("database", array(
    "development" => array(
        "driver" => "mysql",
        "host" => "10.0.1.4",
        "user" => "root",
        "password" => "lithium@13",
        "database" => "lalazdb",
        "prefix" => ""
    ),
    "test" => array(
        "driver" => "",
        "host" => "",
        "user" => "",
        "password" => "",
        "database" => "",
        "prefix" => ""
    ),
    "production" => array(
        "driver" => "",
        "host" => "",
        "user" => "",
        "password" => "",
        "database" => "",
        "prefix" => ""
    )
));
?>