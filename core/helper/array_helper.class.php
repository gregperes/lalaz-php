<?php
/**
 * Helper methods for array.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class ArrayHelper extends Object {
    
    public static function getValue($key, $array = array()) {
        if (!empty($key) && is_array($array)) {
            return array_key_exists($key, $array) ? $array[$key] : null;
        }
        
        return null;
    }
    
    public static function clean($array = array()) {
        foreach($array as $key => $value) {
            if (strlen($value) == 0) {
                unset($array[$key]);
            }
        }
    }
}
?>