<?php
/**
 *  Defines methods for take care of the security of the application.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class Security extends Object {
    
    /**
      *  @copyright Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
      */
    public static function cipher($text, $key) {
        if(empty($key)) {
            trigger_error("You cannot use an empty key for Security::cipher()", E_USER_WARNING);
            return null;
        }
        
        if (!defined("CIPHER_SEED")) {
            define("CIPHER_SEED", "76859309657453542496749683645");
        }
        
        srand(CIPHER_SEED);
        $output = "";
        
        for($i = 0; $i < strlen($text); $i++) {
            for($j = 0; $j < ord(substr($key, $i % strlen($key), 1)); $j++) {
                rand(0, 255);
            }
            
            $mask = rand(0, 255);
            $output .= chr(ord(substr($text, $i, 1)) ^ $mask);
        }
        
        return $output;
    }

    public static function hash($text, $hash = null, $salt = false) {
        if($salt) {
            if(is_string($salt)) {
                $text = $salt . $text;
            } else {
                $text = Config::read("securityKey") . $text;
            }
        }
        
        switch($hash) {
            case "md5":
                return md5($text);
            case "sha256":
                return bin2hex(mhash(MHASH_SHA256, $text));
            case "sha1":
            default:
                return sha1($text);
        }
        
        return false;
    }
}
?>