<?php
/**
 * Helper methods for directories.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class DirectoryHelper extends Object {
    
    public static function combineDirs($dirs = array()) {
        $result = null;
        $count = 0;
        
        foreach ($dirs as $value) {
            $lastChar = substr($value, strlen($value) - 1, 1);
            
            if ($lastChar == DIRECTORY_SEPARATOR) {
                $value = substr($value, 0, strlen($value) - 1);
            }
            
            if ($count > 0) {
                $result .= DIRECTORY_SEPARATOR;
            }
            
            $result .= $value;
            $count++;
        }
        
        return $result;
    }

    public static function createDir($dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }
    
    public static function getSubdirs($base) {
	$dir_array = array();
        
	if (!is_dir($base)) {
		return $dir_array;
	}
		
	if (($dh = opendir($base))) {
            while (($file=readdir($dh)) !== false) {
                if ($file == '.' || $file == '..') { 
                    continue;
                }

                if (is_dir($base . DIRECTORY_SEPARATOR . $file)) {
                    $dir_array[] = $file;
                } else {
                    array_merge($dir_array, rendertask::getAllSubdirectories($base.'/'.$file));
                }
            }
            
            closedir($dh);
            return $dir_array;
	}
        
        return false;
    }
    
    public static function getFiles($dir) {
        if (!is_dir($dir)) {
            return false;
        }

        $dh = opendir($dir);
        $list = array();
        $count = 0;

        while ($item = readdir($dh)) {
            if (!is_dir("$dir/$item")) {
                $list[$count] = $item;
                $count++;
            }
        }

        closedir($dh);
        sort($list);

        $files = array();

        foreach ($list as $key => $value) {
            $file["name"] = $value;
            $file["fullName"] =  $dir . DIRECTORY_SEPARATOR . $value;

            $files[$key] = $file;
        }

        return $files;
    }

    public static function saveFile($file, $dir) {
        return (copy($file, $dir)) ? true : false;
    }
}
?>