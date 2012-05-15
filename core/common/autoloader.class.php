<?php
/**
 * Represents the implementation of the autoload.
 * This class is responsible for load the required class.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class Autoloader {
    
    private static $classPaths = array();
    private static $classFileSuffix = ".class.php";
    private static $cacheFilePath = null;
    private static $cachedPaths = null;
    
    private static function findClass($className, $dir) {
        if (is_dir($dir)) {
            $directory = opendir($dir);
            $classFilePreffix = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $className));

            while($file = readdir($directory)) {
                $file = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $file));
                $filePath = $dir . DIRECTORY_SEPARATOR . $file ;

                $s = realpath($dir);

                if($file == "." || $file == ".." || is_dir($file)) {
                    continue;
                } else if($file == $classFilePreffix . self::$classFileSuffix) {
                    return $filePath;
                }
            }
        }

        return false ;
    }
    
    public static function setClassFileSuffix($value) {
        self::$classFileSuffix = $value;
    }
    
    public static function getCachedPath($className) {
        if (isset(self::$cachedPaths[$className])) {
            return self::$cachedPaths[$className];
        } else {
            return false;
        }
    }
    
    public static function addClassPath($path) {
        self::$classPaths[] = $path;
    }
    
    public static function register($className) {
        $filePath = self::getCachedPath($className);
        
        if ($filePath && file_exists($filePath)) {
            require_once($filePath);
            return true;
        } else {
            foreach (self::$classPaths as $path) {
                $filePath = self::findClass($className, $path);
                
                if ($filePath) {
                    self::$cacheFilePath[$className] = $filePath;
                    require_once($filePath);
                    return true;
                }
            }
        }
        
        die("{$className} was not found.");
    }
}
?>