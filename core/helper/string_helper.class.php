<?php
/**
 * Helper methods for strings.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class StringHelper extends Object {

    public static function compress($string) {
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $string);
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
        
        return $buffer;
    }

    public static function camelize($value, $separator = "_") {
        return str_replace(" ", "", ucwords(str_replace($separator, " ", $value)));
    }
    
    public static function underscore($string) {
        return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $string));
    }
    
    public static function underscoreToDirectory($string) {
        return str_replace("_", DIRECTORY_SEPARATOR, $string);
    }
    
    public static function extractFileName($path) {
        $filename = substr($path, strripos($path, DIRECTORY_SEPARATOR) + 1, strlen($path));
        return $filename;
    }
}
?>