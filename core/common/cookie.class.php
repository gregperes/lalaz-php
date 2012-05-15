<?php
/**
 * Controls the COOKIES of the application.
 * 
 * This class is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 * 
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class Cookie extends Singleton {
    
    public $expires;
    public $path = "/";
    public $domain = "";
    public $secure = false;
    public $key = "#@lalaz@#!";
    public $name = "LalazCookie";
    
    protected function __construct() {}
    
    public static function set($key, $value) {
        $self = self::getInstance("Cookie");
        
        if(isset($self->$key)) {
            $self->$key = $value;
            return true;
        }
        
        return false;
    }

    public static function get($key) {
        $self = self::getInstance("Cookie");
        
        if(isset($self->$key)) {
            return $self->$key;
        }
        
        return null;
    }

    public static function delete($name) {
        $self = self::getInstance("Cookie");
        $path = Mapper::normalize(Mapper::base() . $self->path);
        return setcookie("{$self->name}[{$name}]", "", time() - 42000, $path, $self->domain, $self->secure);
    }
    
    public static function read($name) {
        $self = self::getInstance("Cookie");
        return self::decrypt($_COOKIE[$self->name][$name]);
    }

    public static function write($name, $value, $expires = null) {
        $self = self::getInstance("Cookie");
        $expires = $self->expire($expires);
        $path = Mapper::normalize(Mapper::base() . $self->path);
        return setcookie("{$self->name}[{$name}]", self::encrypt($value), $expires, $path, $self->domain, $self->secure);
    }

    public static function encrypt($value) {
        $self = self::getInstance("Cookie");
        $encripted = base64_encode(Security::cipher($value, $self->key));
        return "U3BhZ2hldHRp.{$encripted}";
    }

    public static function decrypt($value) {
        $self = self::getInstance("Cookie");
        $prefix = strpos($value, "U3BhZ2hldHRp.");
        
        if($prefix !== false) {
            $encrypted = base64_decode(substr($value, $prefix + 13));
            return Security::cipher($encrypted, $self->key);
        }
        
        return false;
    }
    
    public function expire($expires) {
        if(is_null($expires)) {
            $expires = $this->expires;
        }
        
        $now = time();
        
        if(is_numeric($expires)) {
            return $this->expires = $now + $expires;
        } else {
            return $this->expires = strtotime($expires, $now);
        }
    }
}
?>