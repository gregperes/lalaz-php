<?php
/**
 * Represents the factory pattern for create the Databases objects.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class DatabaseFactory extends Object {
    
    /**
     * Create a database intance based on the driver that is defined in the configuration
     * 
     * @return A instance of Database class
     */
    public static function createDatabase() {
        $database = null;
        $environment = Config::read("environment");
        $configs = Config::read("database");
        $config = ArrayHelper::getValue($environment, $configs);
        
        if (is_null($config) || empty($config)) {
            $error = new Error("Configuration Error", "The database configuration for environment $environment was not found.");
            $error->render();
        }
        
        $driver = ArrayHelper::getValue("driver", $config);

        switch ($driver) {
            case "mysql" :
                $database = new MysqlDatabase($config);
                break;

            case "postgres" :
                $database = new PostgresDatabase($config);
                break;

            default :
                $error = new Error("Configuration Error", "The driver $driver was not implemented.");
                $error->render();
        }

        return $database;
    }
}
?>