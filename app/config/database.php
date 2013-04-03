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
        "driver" => "postgres",
        "host" => "localhost",
        "user" => "postgres",
        "password" => "123456",
        "database" => "lalazdb",
        "prefix" => "tb"
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